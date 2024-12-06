<?php

namespace IndieWeb;

use mf2\Parser as Mf2Parser;
use BarnabyWalters\Mf2;

ob_start();
require __DIR__ . '/bootstrap.php';
ob_end_clean();

if (PHP_SAPI === 'cli') {
    $html = file_get_contents(__DIR__ . '/rel-me-test.html');
    $parser = new Mf2Parser($html);
    $mf = $parser->parse();
    
    $testSuites = Mf2\findMicroformatsByType($mf, 'h-x-test-suite');
    
    if (count($testSuites) === 0) {
        die("Found no test suites in rel-me-test.html");
    }
    
    $testSuite = $testSuites[0];
    
    echo "\n" . Mf2\getProp($testSuite, 'name') . "\n";
    echo "============================\n";
    
    foreach ($testSuite['properties']['x-test-case'] as $testCase) {
        echo "\n";
        $params = $testCase['properties']['x-parameter'];
        $meUrl = array_shift($params);
        if (count($params) === 0) {
            $redirects = mockFollowOneRedirect(array(null));
        } else {
            $redirects = mockFollowOneRedirect($params);
        }
        
        list($expectedUrl, $expectedSecure) = $testCase['properties']['x-expected-result'];
        $expectedSecure = $expectedSecure === 'true';
        
        // Begin testing
        $meUrl = normaliseUrl($meUrl);
        list($url, $secure, $previous) = relMeDocumentUrl($meUrl, $redirects);
        // end testing
        
        if ($url === $expectedUrl and $secure === $expectedSecure) :
            echo "(pass) " . Mf2\getProp($testCase, 'name') . "\n";
     else:
         echo "(fail) " . Mf2\getProp($testCase, 'name') . "\n";
         if ($url != $expectedUrl) {
             echo "- {$url} should match {$expectedUrl}\n";
         }
         if ($secure != $expectedSecure) {
             echo "- {$secure} didn’t match {$expectedSecure}\n";
         }
     endif;
    }
}
