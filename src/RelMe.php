<?php

namespace IndieWeb;

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
	$pass = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
	
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
	
}

function secureMatchUrlRedirects($url) {
	
}
