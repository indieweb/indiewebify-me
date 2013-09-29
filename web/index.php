<?php

namespace Indieweb\IndiewebifyMe;

ob_start();
require __DIR__ . '/../vendor/autoload.php';
ob_end_clean();

use Guzzle;
use mf2;
use Silex;
use Symfony\Component\HttpFoundation as Http;

function renderTemplate($template, array $data = []) {
	$templatePath = __DIR__ . '/../templates/' . $template . '.php';
	$render = function ($__templateData, $__path) {
		ob_start();
		extract($__templateData);
		unset($__templateData);
		include $__path;
		return ob_get_clean();
	};
	
	return $render($data, $templatePath);
}

function render($template, array $data = []) {
	$isHtml = pathinfo($template, PATHINFO_EXTENSION) === 'html';
	$out = '';
	if ($isHtml)
		$out .= renderTemplate('header.html', $data);
	$out .= renderTemplate($template, $data);
	if ($isHtml)
		$out .= renderTemplate('footer.html', $data);
	return $out;
}

// Web server setup

// Route static assets from CLI server
if (PHP_SAPI === 'cli-server') {
	if (file_exists(__DIR__ . $_SERVER['REQUEST_URI']) and !is_dir(__DIR__ . $_SERVER['REQUEST_URI'])) {
		return false;
	}
}

$app = new Silex\Application();

$app->get('/', function () {
	return render('index.html');
});

$app->get('/validate-rels', function (Http\Request $request) {
	
});

$app->run();
