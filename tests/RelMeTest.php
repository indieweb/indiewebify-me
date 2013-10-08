<?php

namespace IndieWeb;

use PHPUnit_Framework_TestCase;

/**
 * RelMeTest
 *
 * @author barnabywalters
 */
class RelMeTest extends PHPUnit_Framework_TestCase {
	public function testUnparseUrl() {
		$this->assertEquals('http://example.com/', unparseUrl(parse_url('http://example.com')));
		$this->assertEquals('http://example.com/?thing&amp;more', unparseUrl(parse_url('http://example.com?thing&amp;more')));
	}
	
	public function testNormaliseUrl() {
		$this->assertEquals('http://example.com/', normaliseUrl('http://example.com'));
		$this->assertEquals('http://example.com/?thing=1', normaliseUrl('http://example.com?thing=1'));
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
		$result = http_parse_headers($test);
		$this->assertEquals($expected, $result);
	}
	
	/** @group network */
	public function testFollowOneRedirect() {
		$this->assertEquals('https://brennannovak.com/', followOneRedirect('http://brennannovak.com'));
	}
	
	public function testRelMeDocumentUrlHandlesNoRedirect() {
		$chain = mockFollowOneRedirect(array(null));
		$meUrl = normaliseUrl('http://example.com');
		list($url, $isSecure, $previous) = relMeDocumentUrl($meUrl, $chain);
		$this->assertEquals($meUrl, $url);
		$this->assertTrue($isSecure);
		$this->assertCount(0, $previous);
	}
	
	public function testRelMeDocumentUrlHandlesSingleSecureHttpRedirect() {
		$finalUrl = normaliseUrl('http://example.org');
		$chain = mockFollowOneRedirect(array($finalUrl));
		$meUrl = normaliseUrl('http://example.com');
		list($url, $isSecure, $previous) = relMeDocumentUrl($meUrl, $chain);
		$this->assertEquals($finalUrl, $url);
		$this->assertTrue($isSecure);		
		$this->assertContains($finalUrl, $previous);
	}
	
	public function testRelMeDocumentUrlHandlesMultipleSecureHttpRedirects() {
		$finalUrl = normaliseUrl('http://example.org');
		$intermediateUrl = normaliseUrl('http://www.example.org');
		$chain = mockFollowOneRedirect(array($intermediateUrl, $finalUrl));
		$meUrl = normaliseUrl('http://example.com');
		list($url, $isSecure, $previous) = relMeDocumentUrl($meUrl, $chain);
		$this->assertEquals($finalUrl, $url);
		$this->assertTrue($isSecure);
		$this->assertContains($intermediateUrl, $previous);
	}
	
	public function testRelMeDocumentUrlHandlesSingleSecureHttpsRedirect() {
		$finalUrl = normaliseUrl('https://example.org');
		$chain = mockFollowOneRedirect(array($finalUrl));
		$meUrl = normaliseUrl('https://example.com');
		list($url, $isSecure, $previous) = relMeDocumentUrl($meUrl, $chain);
		$this->assertEquals($finalUrl, $url);
		$this->assertTrue($isSecure);		
		$this->assertContains($finalUrl, $previous);
	}
	
	public function testRelMeDocumentUrlHandlesMultipleSecureHttpsRedirects() {
		$finalUrl = normaliseUrl('https://example.org');
		$intermediateUrl = normaliseUrl('https://www.example.org');
		$chain = mockFollowOneRedirect(array($intermediateUrl, $finalUrl));
		$meUrl = normaliseUrl('https://example.com');
		list($url, $isSecure, $previous) = relMeDocumentUrl($meUrl, $chain);
		$this->assertEquals($finalUrl, $url);
		$this->assertTrue($isSecure);
		$this->assertContains($intermediateUrl, $previous);
	}
	
	public function testRelMeDocumentUrlReportsInsecureRedirect() {
		$finalUrl = normaliseUrl('http://example.org');
		$intermediateUrl = normaliseUrl('https://www.example.org');
		$chain = mockFollowOneRedirect(array($intermediateUrl, $finalUrl));
		$meUrl = normaliseUrl('https://example.com');
		list($url, $isSecure, $previous) = relMeDocumentUrl($meUrl, $chain);
		$this->assertFalse($isSecure);
		$this->assertContains($intermediateUrl, $previous);
	}
	
	public function testRelMeLinksFindsLinks() {
		$relMeLinks = relMeLinks(<<<EOT
<link rel="me" href="http://example.org" />
<a rel="me" href="http://twitter.com/barnabywalters">Me</a>
EOT
			);
		$this->assertEquals(array('http://example.org', 'http://twitter.com/barnabywalters'), $relMeLinks);
	}
	
	// backlinkingRelMeSuccessNoRedirect tests
	
	public function testBacklinkingRelMeSuccessNoRedirect() {
		$meUrl = $backlinkingMeUrl = 'http://example.com';
		$chain = mockFollowOneRedirect(array($backlinkingMeUrl));
		list($matches, $secure, $previous) = backlinkingRelMeUrlMatches($backlinkingMeUrl, $meUrl, $chain);
		$this->assertTrue($matches);
		$this->assertTrue($secure);
	}
	
	public function testBacklinkingRelMeSuccessOneRedirect() {
		$meUrl = 'http://example.com';
		$backlinkingMeUrl = 'http://example.org';
		$chain = mockFollowOneRedirect(array($backlinkingMeUrl, $meUrl));
		list($matches, $secure, $previous) = backlinkingRelMeUrlMatches($backlinkingMeUrl, $meUrl, $chain);
		$this->assertTrue($matches);
		$this->assertTrue($secure);
	}
	
	public function testBacklinkingRelMeNoMatchInsecureRedirect() {
		$meUrl = 'http://example.com';
		$backlinkingMeUrl = 'http://example.org';
		$chain = mockFollowOneRedirect(array($backlinkingMeUrl, 'https://example.org'));
		list($matches, $secure, $previous) = backlinkingRelMeUrlMatches($backlinkingMeUrl, $meUrl, $chain);
		$this->assertFalse($matches);
		$this->assertFalse($secure);
	}
	
	public function testBacklinkingRelMeSuccessInsecureRedirect() {
		$meUrl = 'http://example.org';
		$backlinkingMeUrl = 'http://example.com';
		$chain = mockFollowOneRedirect(array($backlinkingMeUrl, 'https://example.org'));
		list($matches, $secure, $previous) = backlinkingRelMeUrlMatches($backlinkingMeUrl, $meUrl, $chain);
		$this->assertTrue($matches);
		$this->assertFalse($secure);
	}
	
	public function testBacklinkingRelMeSecureRedirectNoMatch() {
		$meUrl = 'http://example.org';
		$backlinkingMeUrl = 'http://example.com';
		$chain = mockFollowOneRedirect(array($backlinkingMeUrl, 'http://foo.org'));
		list($matches, $secure, $previous) = backlinkingRelMeUrlMatches($backlinkingMeUrl, $meUrl, $chain);
		$this->assertFalse($matches);
		$this->assertTrue($secure);
	}
}
