<?php

/**
 * RelMeTest
 *
 * PHP version 7.4
 *
 * @category Tests
 * @package  IndieWeb
 * @author   Display Name <example@example.org>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://indiewebify.me
 */

namespace IndieWeb;

use PHPUnit\Framework\TestCase;

/**
 * RelMeTest
 *
 * @category Tests
 * @package  IndieWeb
 * @author   Display Name <example@example.org>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://indiewebify.me
 */
class RelMeTest extends TestCase
{
    /**
     * Test Add Dashes To Date
     *
     * @return void
     */
    public function testUnparseUrl()
    {
        $this->assertEquals('http://example.com/', unparseUrl(parse_url('http://example.com')));
        $this->assertEquals('http://example.com/?thing&amp;more', unparseUrl(parse_url('http://example.com?thing&amp;more')));
    }

    /**
     * Test Normalise URL
     *
     * @return void
     */
    public function testNormaliseUrl()
    {
        $this->assertEquals('http://example.com/', normaliseUrl('http://example.com'));
        $this->assertEquals('http://example.com/?thing=1', normaliseUrl('http://example.com?thing=1'));
    }

    /**
     * Test HTTP parse headers
     *
     * @return void
     */
    public function testHttpParseHeaders()
    {
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

    //    /**
    //     * @group network
    //     */
    //    /* There is already a test for this in indieweb/rel-me
    //    public function testFollowOneRedirect() {
    //    $this->assertEquals('https://brennannovak.com/', followOneRedirect('http://brennannovak.com'));
    //    }*/

    /**
     * Test relMeDocumentUrl handles no redirect
     *
     * @return void
     */
    public function testRelMeDocumentUrlHandlesNoRedirect()
    {
        $chain = mockFollowOneRedirect(array(null));
        $meUrl = normaliseUrl('http://example.com');
        list($url, $isSecure, $previous) = relMeDocumentUrl($meUrl, $chain);
        $this->assertEquals($meUrl, $url);
        $this->assertTrue($isSecure);
    }

    /**
     * Test relMeDocumentUrl handles single secure http redirect
     *
     * @return void
     */
    public function testRelMeDocumentUrlHandlesSingleSecureHttpRedirect()
    {
        $finalUrl = normaliseUrl('http://example.org');
        $chain = mockFollowOneRedirect(array($finalUrl));
        $meUrl = normaliseUrl('http://example.com');
        list($url, $isSecure, $previous) = relMeDocumentUrl($meUrl, $chain);
        $this->assertEquals($finalUrl, $url);
        $this->assertTrue($isSecure);
        $this->assertContains($finalUrl, $previous);
    }

    /**
     * Test relMeDocumentUrl handles multiple redirects
     *
     * @return void
     */
    public function testRelMeDocumentUrlHandlesMultipleSecureHttpRedirects()
    {
        $finalUrl = normaliseUrl('http://example.org');
        $intermediateUrl = normaliseUrl('http://www.example.org');
        $chain = mockFollowOneRedirect(array($intermediateUrl, $finalUrl));
        $meUrl = normaliseUrl('http://example.com');
        list($url, $isSecure, $previous) = relMeDocumentUrl($meUrl, $chain);
        $this->assertEquals($finalUrl, $url);
        $this->assertTrue($isSecure);
        $this->assertContains($intermediateUrl, $previous);
    }

    /**
     * Test relMeDocumentUrl handles multiple redirects
     *
     * @return void
     */
    public function testRelMeDocumentUrlHandlesSingleSecureHttpsRedirect()
    {
        $finalUrl = normaliseUrl('https://example.org');
        $chain = mockFollowOneRedirect(array($finalUrl));
        $meUrl = normaliseUrl('https://example.com');
        list($url, $isSecure, $previous) = relMeDocumentUrl($meUrl, $chain);
        $this->assertEquals($finalUrl, $url);
        $this->assertTrue($isSecure);
        $this->assertContains($finalUrl, $previous);
    }

    /**
     * Test relMeDocumentUrl handles multiple redirects
     *
     * @return void
     */
    public function testRelMeDocumentUrlHandlesMultipleSecureHttpsRedirects()
    {
        $finalUrl = normaliseUrl('https://example.org');
        $intermediateUrl = normaliseUrl('https://www.example.org');
        $chain = mockFollowOneRedirect(array($intermediateUrl, $finalUrl));
        $meUrl = normaliseUrl('https://example.com');
        list($url, $isSecure, $previous) = relMeDocumentUrl($meUrl, $chain);
        $this->assertEquals($finalUrl, $url);
        $this->assertTrue($isSecure);
        $this->assertContains($intermediateUrl, $previous);
    }

    /**
     * Test relMeDocumentUrl handles insecure redirect
     *
     * @return void
     */
    public function testRelMeDocumentUrlReportsInsecureRedirect()
    {
        $finalUrl = normaliseUrl('http://example.org');
        $intermediateUrl = normaliseUrl('https://www.example.org');
        $chain = mockFollowOneRedirect(array($intermediateUrl, $finalUrl));
        $meUrl = normaliseUrl('https://example.com');
        list($url, $isSecure, $previous) = relMeDocumentUrl($meUrl, $chain);
        $this->assertFalse($isSecure);
        $this->assertContains($intermediateUrl, $previous);
    }

    /**
     * Test relMeLinks finds links
     *
     * @return void
     */
    public function testRelMeLinksFindsLinks()
    {
        $relMeLinks = relMeLinks(
            <<<EOT
<link rel="me" href="http://example.org" />
<a rel="me" href="http://twitter.com/barnabywalters">Me</a>
EOT
            , 'http://example.com'
        );
        $this->assertEquals(array('http://example.org', 'http://twitter.com/barnabywalters'), $relMeLinks);
    }

    /**
     * BacklinkingRelMeSuccessNoRedirect tests
     *
     * @return void
     */
    public function testBacklinkingRelMeSuccessNoRedirect()
    {
        $meUrl = $backlinkingMeUrl = 'http://example.com';
        $chain = mockFollowOneRedirect(array($backlinkingMeUrl));
        list($matches, $secure, $previous) = backlinkingRelMeUrlMatches($backlinkingMeUrl, $meUrl, $chain);
        $this->assertTrue($matches);
        $this->assertTrue($secure);
    }

    public function testBacklinkingRelMeSuccessOneRedirect()
    {
        $meUrl = 'http://example.com';
        $backlinkingMeUrl = 'http://example.org';
        $chain = mockFollowOneRedirect(array($backlinkingMeUrl, $meUrl));
        list($matches, $secure, $previous) = backlinkingRelMeUrlMatches($backlinkingMeUrl, $meUrl, $chain);
        $this->assertTrue($matches);
        $this->assertTrue($secure);
    }

    public function testBacklinkingRelMeNoMatchInsecureRedirect()
    {
        $meUrl = 'http://example.com';
        $backlinkingMeUrl = 'http://example.org';
        $chain = mockFollowOneRedirect(array($backlinkingMeUrl, 'https://example.org'));
        list($matches, $secure, $previous) = backlinkingRelMeUrlMatches($backlinkingMeUrl, $meUrl, $chain);
        $this->assertFalse($matches);
        $this->assertFalse($secure);
    }

    public function testBacklinkingRelMeSuccessInsecureRedirect()
    {
        $meUrl = 'http://example.org';
        $backlinkingMeUrl = 'http://example.com';
        $chain = mockFollowOneRedirect(array($backlinkingMeUrl, 'https://example.org'));
        list($matches, $secure, $previous) = backlinkingRelMeUrlMatches($backlinkingMeUrl, $meUrl, $chain);
        $this->assertTrue($matches);
        $this->assertFalse($secure);
    }

    public function testBacklinkingRelMeSecureRedirectNoMatch()
    {
        $meUrl = 'http://example.org';
        $backlinkingMeUrl = 'http://example.com';
        $chain = mockFollowOneRedirect(array($backlinkingMeUrl, 'http://foo.org'));
        list($matches, $secure, $previous) = backlinkingRelMeUrlMatches($backlinkingMeUrl, $meUrl, $chain);
        $this->assertFalse($matches);
        $this->assertTrue($secure);
    }
}
