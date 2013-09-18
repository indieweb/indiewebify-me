<?php

ob_start();
include 'vendor/autoload.php';

// Parse Microformats
use mf2\Parser;

// Get URL
$url = filter_var(trim($_POST['url']), FILTER_SANITIZE_URL);
$url = web_address_to_uri($url, true);

if (empty($url)) {
	ob_end_clean();
	header('No URL', false, 400);
	header('Content-type: application/json');
	echo json_encode(['error' => 'No URL']);
	die;
}

$request = new Buzz\Message\Request('GET', '/', $url);
$response = new Buzz\Message\Response();

$client = new Buzz\Client\Curl();

// do not check https validity
$client->setVerifyPeer(true);

// define your user agent
$client->setOption('CURLOPT_USERAGENT', 'IndieWebify microformat tool http://indiewebify.me');
$client->setOption('CURLOPT_COOKIEFILE', true);
$client->setOption('CURLOPT_COOKIEJAR', true);
$client->send($request, $response);

ob_end_clean();

if ($response->isOk()){
	$parser = new Parser($response->getContent());
	$output = $parser->parse();
	
	header('Content-type: application/json');
	echo json_encode($output);
} else {
	header('Content-type: application/json');
	echo json_encode(['error' => 'Could not fetch URL']);
}


