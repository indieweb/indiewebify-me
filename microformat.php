<?php

include 'vendor/autoload.php';

use mf2\Parser;

$parser = new Parser('<div class="h-card"><p class="p-name">Barnaby Walters</p></div>');
$output = $parser->parse();

// Really simple using a static facade
Guzzle\Http\StaticClient::mount();
$response = Guzzle::get('https://brennannovak.com/notes/325');


echo '<pre>';
print_r($response);