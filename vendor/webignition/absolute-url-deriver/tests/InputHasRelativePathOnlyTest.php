<?php

class InputHasRelativePathOnlyTest extends PHPUnit_Framework_TestCase {   
    
    public function testRelativePathIsTransformedIntoCorrectAbsoluteUrl() {
        $deriver = new \webignition\AbsoluteUrlDeriver\AbsoluteUrlDeriver(
            'server.php?param1=value1',
            'http://www.example.com/pathOne/pathTwo/pathThree'
        );

        $this->assertEquals('http://www.example.com/pathOne/pathTwo/pathThree/server.php?param1=value1', (string)$deriver->getAbsoluteUrl());
    }   
}