<?php

include 'vendor/autoload.php';

// Parse Microformats
use mf2\Parser;

// Get URL
$request = new Buzz\Message\Request('GET', '/', $_POST['url']);
$response = new Buzz\Message\Response();

$client = new Buzz\Client\Curl();

// do not check https validity
$client->setVerifyPeer(true);

// define your user agent
$client->setOption('CURLOPT_USERAGENT', 'IndieWebify microformat tool http://indiewebify.me');
$client->setOption('CURLOPT_COOKIEFILE', true);
$client->setOption('CURLOPT_COOKIEJAR', true);
$client->send($request, $response);


if ($response->isOk())
{

	$parser = new Parser($response->getContent());
	$output = $parser->parse();

	echo json_encode($output);

}


