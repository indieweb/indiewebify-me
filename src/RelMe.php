<?php

namespace IndieWeb;

if (!function_exists('http_parse_headers')) {
	function http_parse_headers($raw_headers) {
		$headers = array();
		$key = '';

		foreach (explode("\n", $raw_headers) as $i => $h) {
			$h = explode(':', $h, 2);

			if (isset($h[1])) {
				if (!isset($headers[$h[0]]))
					$headers[$h[0]] = trim($h[1]);
				elseif (is_array($headers[$h[0]])) {
					$headers[$h[0]] = array_merge($headers[$h[0]], array(trim($h[1])));
				} else {
					$headers[$h[0]] = array_merge(array($headers[$h[0]]), array(trim($h[1])));
				}

				$key = $h[0];
			} else {
				if (substr($h[0], 0, 1) == "\t")
					$headers[$key] .= "\r\n\t" . trim($h[0]);
				elseif (!$key)
					$headers[0] = trim($h[0]);trim($h[0]);
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

function followOneRedirect($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	$response = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);

	$rawHeaders = mb_substr($response, 0, $info['header_size']);
	$headers = http_parse_headers($rawHeaders);
	$body = mb_substr($response, $info['header_size']);
}

function secureMatchUrlRedirects($url) {
	
}
