<?php

namespace Indieweb\IndiewebifyMe;

ob_start();
require __DIR__ . '/../vendor/autoload.php';
ob_end_clean();

use BarnabyWalters\Mf2;
use DateTime;
use Guzzle;
use HTMLPurifier, HTMLPurifier_Config;
use IndieWeb;
use IndieWeb\MentionClient;
use Mf2\Parser as MfParser;
use Silex;
use Symfony\Component\HttpFoundation as Http;

function renderTemplate($template, array $__templateData = array()) {
	$render = function ($__path, $render=null) use ($__templateData) {
		ob_start();
		extract($__templateData);
		unset($__templateData);
		include __DIR__ . '/../templates/' . $__path . '.php';
		return ob_get_clean();
	};
	
	return $render($template, $render);
}

function render($template, array $data = array()) {
	$isHtml = pathinfo($template, PATHINFO_EXTENSION) === 'html';
	$out = '';
	
	$purifierConfig = HTMLPurifier_Config::createDefault();
	$data['purify'] = array(new HTMLPurifier($purifierConfig), 'purify');
	
	if ($isHtml)
		$out .= renderTemplate('header.html', $data);
	$out .= renderTemplate($template, $data);
	if ($isHtml)
		$out .= renderTemplate('footer.html', $data);
	return $out;
}

function crossOriginResponse($resp, $code=200) {
	$response = ($resp instanceof Http\Response) ? $resp : new Http\Response($resp, $code);
	$response->headers->set('Access-Control-Allow-Origin', '*');
	return $response;
}

function httpGet($url) {
	$client = new Guzzle\Http\Client();
	ob_start();
	$url = web_address_to_uri($url, true);
	ob_end_clean();
	
	try {
		$response = $client->get($url)->send();
		return array($response, null);
	} catch (Guzzle\Common\Exception\GuzzleException $e) {
		return array(null, $e);
	}
}

function parseMf($resp, $url) {
	$parser = new MfParser((string) $resp, $url);
	return $parser->parse();
}

function fetchMf($url) {
	list($resp, $err) = httpGet($url);
	if ($err)
		return array(null, $err);
	return array(parseMf($resp->getBody(), $url), null);
}

function errorResponder($template, $url) {
	return function ($message, $code = 400) use ($template, $url) {
		return crossOriginResponse(render($template, array(
			'error' => array('message' => $message),
			'url' => htmlspecialchars($url)
		)), $code);
	};
}

function isWordpressDomain($url) {
	return stristr(parse_url($url, PHP_URL_HOST), '.wordpress.com') !== false;
}

function isGithubDomain($url) {
	return stristr(parse_url($url, PHP_URL_HOST), '.github.') !== false;
}

function isTumblrDomain($url) {
	return stristr(parse_url($url, PHP_URL_HOST), '.tumblr.com') !== false;
}

function detectBloggingSoftware($response) {
	$d = new MfParser($response->getBody(1), $response->getEffectiveUrl());
	foreach ($d->query('//meta[@name="generator"]') as $generatorEl) {
		if (stristr($generatorEl->getAttribute('content'), 'wordpress') !== false)
			return 'wordpress';
		if (stristr($generatorEl->getAttribute('content'), 'mediawiki') !== false)
			return 'mediawiki';
		if (stristr($generatorEl->getAttribute('content'), 'idno') !== false)
			return 'idno';
	}
	
	return null;
}

function hEntryName(array $hEntry) {
	$compare = Mf2\hasProp($hEntry, 'content') ? Mf2\getProp($hEntry, 'content') : (isset($hEntry['value']) ? $hEntry['value'] : null);
	if (mb_strlen(Mf2\getProp($hEntry, 'name')) < mb_strlen($compare))
		return Mf2\getProp($hEntry, 'name');
	return null;
}

function datetimeProblem($datetimeStr) {
	try {
		$dt = new DateTime($datetimeStr);
	} catch (Exception $e) {
		return "The datetime is not valid ISO-8601.";
	}
	
	if (strlen($datetimeStr) < 11) {
		return "Datetimes should be precise to at least the nearest second.";
	} elseif (strlen($datetimeStr) < 19)
		return "The datetime has no timezone.";
	return false;
}

// Web server setup

// Route static assets from CLI server
if (PHP_SAPI === 'cli-server') {
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	
	if (file_exists(__DIR__ . $_SERVER['REQUEST_URI']) and !is_dir(__DIR__ . $_SERVER['REQUEST_URI'])) {
		return false;
	}
} else {
	error_reporting(0);
}

$app = new Silex\Application();

$app->get('/', function () {
	return render('index.html');
});

$app->get('/validate-rel-me/', function (Http\Request $request) {
	if (!$request->query->has('url')) {
		return render('validate-rel-me.html');
	} else {
		ob_start();
		$url = web_address_to_uri($request->query->get('url'), true);
		ob_end_clean();
		
		$errorResponse = errorResponder('validate-rel-me.html', $url);
		
		if (empty($url))
			return $errorResponse('Empty URLs lead nowhere');
		
		list($relMeUrl, $secure, $previous) = IndieWeb\relMeDocumentUrl($url);
		
		if (!$secure)
			return $errorResponse("Insecure redirect between <code>{$previous[count($previous)-2]}</code> and <code>{$previous[count($previous)-1]}</code>");
		
		list($resp, $err) = httpGet($relMeUrl);
		
		if ($err)
			return $errorResponse(htmlspecialchars($err->getMessage()));
		
		$relMeLinks = IndieWeb\relMeLinks($resp->getBody(true), $relMeUrl);
		
		if (empty($relMeLinks))
			return $errorResponse("No <code>rel=me</code> links could be found!");
		
		return crossOriginResponse(render('validate-rel-me.html', array(
			'rels' => $relMeLinks,
			'url' => htmlspecialchars($url),
			'bloggingSoftware' => detectBloggingSoftware($resp)
		)));
	}
});

// TODO: currently this assumes that url2 has been found as an outbound rel-me link
// on url1 — that url1 links to url2 is NOT checked
// TODO: maybe encapsulate the one-directional checking into a function
$app->get('/rel-me-links/', function (Http\Request $request) {
	if (!$request->query->has('url1') or !$request->query->has('url2'))
		return crossOriginResponse('Provide both url1 and url2 parameters', 400);
	// url1 is me, url2 is external profile page
	$url1 = $request->query->get('url1');
	$url2 = $request->query->get('url2');
	
	$meUrl = IndieWeb\normaliseUrl($url1);
	
	list($profileUrl, $secure, $previous) = IndieWeb\relMeDocumentUrl($url2);
	if (!$secure)
		return crossOriginResponse("Inbound rel-me URL redirects insecurely" . print_r($previous, true), 400);
	
	list($resp, $err) = httpGet($profileUrl);
	if ($err)
		return crossOriginResponse("HTTP error when fetching inbound rel me document URL {$profileUrl}: {$err->getMessage()}", 400);
	
	$relMeLinks = IndieWeb\relMeLinks($resp->getBody(true), $profileUrl);
	
	foreach ($relMeLinks as $inboundRelMeUrl) {
		list($matches, $secure, $previous) = IndieWeb\backlinkingRelMeUrlMatches($inboundRelMeUrl, $meUrl);
		if ($matches and $secure)
			return crossOriginResponse('true', 200);
	}

	return crossOriginResponse('false', 200);
});

$app->get('/validate-h-card/', function (Http\Request $request) {
	if (!$request->query->has('url')) {
		return render('validate-h-card.html');
	} else {
		$url = trim($request->query->get('url'));
		
		$errorResponse = errorResponder('validate-h-card.html', $url);
		
		if (empty($url))
			return $errorResponse('Empty URLs lead nowhere');
		
		list($mfs, $err) = fetchMf($url);
		
		if ($err)
			return $errorResponse(htmlspecialchars($err->getMessage()));
		
		$hCards = Mf2\findMicroformatsByType($mfs, 'h-card');
		
		if (count($hCards) === 0)
			return $errorResponse('No h-cards found — check your classnames');
		
		return crossOriginResponse(render('validate-h-card.html', array(
			'hCard' => $hCards[0],
			'url' => htmlspecialchars($url)
		)));
	}
});

$app->get('/validate-h-entry/', function (Http\Request $request) {
	if (!$request->query->has('url')) {
		return render('validate-h-entry.html');
	} else {
		ob_start();
		$url = web_address_to_uri($request->query->get('url'), true);
		ob_end_clean();
		$errorResponse = errorResponder('validate-h-entry.html', $url);
		
		if (empty($url))
			return $errorResponse('Empty URLs lead nowhere');
		
		list($mfs, $err) = fetchMf($url);
		
		if ($err)
			return $errorResponse(htmlspecialchars($err->getMessage()));
		
		$hEntries = Mf2\findMicroformatsByType($mfs, 'h-entry');
		
		if (count($hEntries) === 0)
			return $errorResponse('No h-entries found — check your classnames');
		
		$hEntry = $hEntries[0];
		
		if (Mf2\hasProp($hEntry, 'in-reply-to')) {
			$postType = 'reply';
		} elseif (Mf2\hasProp($hEntry, 'like-of')) {
			$postType = 'like';
		} elseif (Mf2\hasProp($hEntry, 'repost-of')) {
			$postType = 'repost';
		} else {
			$postType = 'post';
		}
		
		return crossOriginResponse(render('validate-h-entry.html', array(
			'postType' => $postType,
			'hEntry' => $hEntry,
			'url' => htmlspecialchars($url)
		)));
	}
});

$app->get('/send-webmentions/', function (Http\Request $request) {
	if (!$request->query->has('url')) {
		return render('send-webmentions.html');
	} else {
		ob_start();
		$url = web_address_to_uri($request->query->get('url'), true);
		ob_end_clean();
		$errorResponse = errorResponder('send-webmentions.html', $url);
		
		if (empty($url))
			return $errorResponse('Empty URLs lead nowhere');
		
		$mentioner = new MentionClient($url);
		$numSent = $mentioner->sendSupportedMentions();
		
		return crossOriginResponse(render('send-webmentions.html', array(
			'numSent' => $numSent,
			'url' => htmlspecialchars($url)
		)));
	}
});

$app->run();
