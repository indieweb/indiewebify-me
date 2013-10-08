<?php

namespace IndieWeb;

const TESTING = true;

ob_start();
require __DIR__ . '/../vendor/autoload.php';
ob_end_clean();

function mockFollowOneRedirect(array $responses) {
	$i = 0;
	$responses = array_values($responses);
	
	return function () use (&$i, $responses) {
		$out = array_key_exists($i, $responses) ? $responses[$i] : null;
		$i = $i + 1;
		return $out;
	};
}