<?php

namespace IndieWeb;

use PHPUnit_Framework_TestCase;
use IndieWeb as IW;

/**
 * RelMeTest
 *
 * @author barnabywalters
 */
class RelMeTest extends PHPUnit_Framework_TestCase {
	public function testNormaliseUrl() {
		$this->assertEquals('http://example.com/', IW\normaliseUrl('http://example.com'));
		$this->assertEquals('http://example.com/?thing=1', IW\normaliseUrl('http://example.com?thing=1'));
	}
}
