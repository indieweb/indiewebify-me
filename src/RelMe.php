<?php

namespace IndieWeb;

use DOMDocument;
use DOMXPath;

/**
 * Adapted from php.net, added TESTING flag and header title case normalisation
 */
if (!function_exists('http_parse_headers') or TESTING) {
	function http_parse_headers($raw_headers) {
		$headers = array();
		$key = '';

		foreach (explode("\n", $raw_headers) as $i => $h) {
			$h = explode(':', $h, 2);
			$headerName = implode('-', array_map('ucfirst', explode('-', $h[0])));
			
			// If dealing with a key:value line
			if (isset($h[1])) {
				if (!isset($headers[$headerName]))
					$headers[$headerName] = trim($h[1]);
				elseif (is_array($headers[$headerName])) {
					$headers[$headerName] = array_merge($headers[$headerName], array(trim($h[1])));
				} else {
					$headers[$headerName] = array_merge(array($headers[$headerName]), array(trim($h[1])));
				}
				
				$key = $headerName;
			} else {
				// dealing with a contined line, $key is the last seen key
				if (substr($h[0], 0, 1) == "\t")
					$headers[$key] .= "\r\n\t" . trim($h[0]);
				elseif (!$key) // I have no idea what this is supposed to be doing
					$headers[0] = trim($h[0]);
			}
		}
		
		return $headers;
	}
}

/**
 * Unparse URL
 * 
 * Given an assoc. array of the form produced by parse_url, return a string
 * 
 * Adapted from http://www.php.net/manual/en/function.parse-url.php#106731
 * 
 * @param  array $parsed_url
 * @return string
 */
function unparseUrl(array $parsed_url) {
	$user = isset($parsed_url['user']) ? $parsed_url['user'] : '';
	$pass = isset($parsed_url['pass']) ? ':' . $parsed_url['pass'] : '';

	return implode('', array(
		isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '',
		$user,
		($user || $pass) ? "$pass@" : '',
		isset($parsed_url['host']) ? $parsed_url['host'] : '',
		isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '',
		isset($parsed_url['path']) ? $parsed_url['path'] : '/',
		isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '',
		isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '',
	));
}

function normaliseUrl($url) {
	return unparseUrl(parse_url($url));
}

function httpGet($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	$response = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);

	$rawHeaders = mb_substr($response, 0, $info['header_size']);
	$headers = http_parse_headers($rawHeaders);
	$body = mb_substr($response, $info['header_size']);
	
	return array($body, $headers, $info);
}

function followOneRedirect($url) {
	list($body, $headers, $info) = httpGet($url);
	
	if (strpos($info['http_code'], '3') === 0 and isset($headers['Location'])) {
		return is_array($headers['Location'])
			? current($headers['Location'])
			: $headers['Location'];
	} else {
		return null;
	}
}

/** return [string URL, bool isSecure, array redirectChain] */
function relMeDocumentUrl($url, $followOneRedirect = null) {
	if (!is_callable($followOneRedirect))
		$followOneRedirect = __NAMESPACE__ . '\followOneRedirect';
	
	$stop = false;
	$previous = array();
	$secure = true;
	$currentUrl = $url;
	while (true) {
		$redirectedUrl = $followOneRedirect($currentUrl);
		if ($redirectedUrl === null):
			break;
		elseif (in_array($redirectedUrl, $previous)):
			break;
		elseif (parse_url($currentUrl, PHP_URL_SCHEME) !== parse_url($redirectedUrl, PHP_URL_SCHEME)):
			$secure = false;
			break;
		else:
			$currentUrl = $redirectedUrl;
			$previous[] = $currentUrl;
		endif;
	}
	
	return array($currentUrl, $secure, $previous);
}

// TODO: should this just accept a HTML string, removing nasty network IO
/** @returns array [array $relMeLinks, bool $err] */
function relMeLinks($url, $httpGet = null) {
	if (!is_callable($httpGet))
		$httpGet = __NAMESPACE__ . '\httpGet';
	
	$relMeLinks = array();
	list($body, $headers, $info) = $httpGet($url);
	if (!isset($headers['Content-Type']) or strpos($headers['Content-Type'], 'html') === false)
		return array($relMeLinks, true);
	
	$doc = new DOMDocument();
	@$doc->loadHTML($body);
	$xpath = new DOMXPath($doc);
	
	foreach ($xpath->query('//*[@href and contains(concat(" ", @rel, " "), " me ")]') as $el) {
		if (trim($el->getAttribute('href')) === '')
			continue;
		
		// TODO: how to handle invalid errors?
		// TODO: should we normalise these URLs?
		$relMeLinks[] = $el->getAttribute('href');
	}
	
	return array(array_unique($relMeLinks), null);
}