<?php

namespace IndieWeb;

use DOMDocument;
use DOMXPath;
use Mf2; // for relative URL resolver

// force loading of resolveUrl function
class_exists('Mf2\Parser');

/**
 * Adapted from php.net, added TESTING flag and header title case normalisation
 */
if (!function_exists('http_parse_headers') or defined('TESTING')) {
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
	return $url === null ? null : unparseUrl(parse_url($url));
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

/**
 * rel-me Document URL
 * 
 * Given a URL, resolves any redirects and returns the resolved URL, whether or not
 * the redirect chain is secure (doesn’t change protocol) and the redirect chain,
 * for inspection.
 * 
 * Example usage:
 * 
 *     list($profileUrl, $isSecure, $redirectChain) = IndieWeb\relMeDocumentUrl($me);
 * 
 * $followOneRedirect defaults to IndieWeb\followOneRedirect but can be replaced for
 * testing purposes.
 * 
 * Returns [string URL, bool isSecure, array redirectChain]
 * @return array
 */
function relMeDocumentUrl($url, $followOneRedirect = null) {
	if (!is_callable($followOneRedirect))
		$followOneRedirect = __NAMESPACE__ . '\followOneRedirect';
	
	$previous = array();
	$secure = true;
	$currentUrl = $url;
	while (true) {
		// TODO: is resolving this URL correct behaviour here?
		// should it be resolved just to the host?
		$redirectedUrl = Mf2\resolveUrl($currentUrl, $followOneRedirect($currentUrl));
		if ($redirectedUrl === null):
			break;
		elseif (in_array($redirectedUrl, $previous)):
			break;
		elseif (parse_url($currentUrl, PHP_URL_SCHEME) !== parse_url($redirectedUrl, PHP_URL_SCHEME)):
			$secure = false;
			$previous[] = $currentUrl = $redirectedUrl;
			break;
		else:
			$currentUrl = $redirectedUrl;
			$previous[] = $currentUrl;
		endif;
	}
	
	return array($currentUrl, $secure, $previous);
}

/**
 * rel-me links
 * given the HTML and URL of a page, returns all the rel-me links found on that page
 * @return array
 */
function relMeLinks($html, $url) {
	$parser = new Mf2\Parser($html, $url);
	$mf = $parser->parse();
	$relMeLinks = @($mf['rels']['me'] ?: array());
	
	return array_unique($relMeLinks);
}

/**
 * URLs match other than scheme
 * Given two URLs, checks if they are the same, ignorning their schemes
 * @todo write tests for this
 * @return bool
 */
function urlsMatchOtherThanScheme($url1, $url2) {
	$p1 = parse_url($url1);
	$p2 = parse_url($url2);
	$p1['scheme'] = 'http';
	$p2['scheme'] = 'http';
	
	return unparseUrl($p1) === unparseUrl($p2);
}

/**
 * Backlinking rel-me URL matches
 * 
 * Used to check whether an inbound (silo to indie homepage) rel-me link can securely
 * be considered to link to the indie profile URL. Given a back-linking URL and a profile
 * URL, returns an array of [(bool) matches, (bool) secure, (array) redirect chain)]
 * 
 * Example Usage:
 * 
 *     list($matches, $secure, $previous) = IndieWeb\backlinkingRelMeUrlMatches($inboundRelMeUrl, $meUrl);
 * 
 * $followOneRedirect defaults to IndieWeb\followOneRedirect but can be replaced for
 * testing purposes.
 * @return array [matches, secure, previous]
 */
function backlinkingRelMeUrlMatches($backlinking, $meUrl, $followOneRedirect=null) {
	if ($followOneRedirect === null)
		$followOneRedirect = __NAMESPACE__ . '\followOneRedirect';
	
	$meUrl = normaliseUrl($meUrl);
	$previous = array();
	$currentUrl = normaliseUrl($backlinking);
	while (true) {
		if ($currentUrl === $meUrl)
			return array(true, true, $previous); // the URLs match and are secure
		
		$redirectedUrl = normaliseUrl($followOneRedirect($currentUrl));
		
		if ($redirectedUrl === null or in_array($redirectedUrl, $previous)):
			return array(false, true, $previous); // The URLs don’t match but are secure
		elseif (parse_url($redirectedUrl, PHP_URL_SCHEME) !== parse_url($currentUrl, PHP_URL_SCHEME)):
			if (urlsMatchOtherThanScheme($redirectedUrl, $meUrl)):
				return array(true, false, $previous);
			else:
				return array(false, false, $previous);
			endif;
		else:
			$currentUrl = $redirectedUrl;
			$previous[] = $currentUrl;
		endif;
	}
}
