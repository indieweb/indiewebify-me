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
	public function testUnparseUrl() {
		$this->assertEquals('http://example.com/', IW\unparseUrl(parse_url('http://example.com')));
		$this->assertEquals('http://example.com/?thing&amp;more', IW\unparseUrl(parse_url('http://example.com?thing&amp;more')));
	}
	
	public function testNormaliseUrl() {
		$this->assertEquals('http://example.com/', IW\normaliseUrl('http://example.com'));
		$this->assertEquals('http://example.com/?thing=1', IW\normaliseUrl('http://example.com?thing=1'));
	}
	
	public function testHttpParseHeaders() {
		$test = <<<EOT
content-type: text/html; charset=UTF-8
Server: Funky/1.0
Set-Cookie: foo=bar
Set-Cookie: baz=quux
Folded: works
	too
EOT;
		$expected = array(
			'Content-Type' => 'text/html; charset=UTF-8',
			'Server' => 'Funky/1.0',
			'Set-Cookie' => array('foo=bar', 'baz=quux'),
			'Folded' => "works\r\n\ttoo"
		);
		$result = IW\http_parse_headers($test);
		$this->assertEquals($expected, $result);
	}
}
