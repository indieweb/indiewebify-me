<?php

ob_start();
require __DIR__ . '/../vendor/autoload.php';
ob_end_clean();

use Silex;

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

$app = new Silex\Application();

$app->get('/', function () {
	return render('index.html');
});

$app->run();
