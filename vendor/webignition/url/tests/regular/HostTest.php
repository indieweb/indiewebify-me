<?php

/**
 *  
 */
class HostTest extends AbstractRegularUrlTest {      
    
    public function testGet() {        
        $url = new \webignition\Url\Url('http://example.com/');
        $this->assertTrue($url->getHost() instanceof \webignition\Url\Host\Host);
        $this->assertEquals("example.com", (string)$url->getHost());
    }   
    
    public function testGetParts() {        
        $url = new \webignition\Url\Url('http://example.com/');
        $this->assertEquals(array(
            'example',
            'com'
        ), $url->getHost()->getParts());
    }      
    
    public function testComparison() {
        $url1 = new \webignition\Url\Url('http://example.com');
        $url2 = new \webignition\Url\Url('http://example.com');
        $url3 = new \webignition\Url\Url('http://www.example.com');
        
        $this->assertTrue($url1->getHost()->equals($url2->getHost()));
        $this->assertFalse($url1->getHost()->equals($url3->getHost()));
    }
    
    public function testEquivalence() {
        $url1 = new \webignition\Url\Url('http://example.com');
        $url2 = new \webignition\Url\Url('http://example.com');
        $url3 = new \webignition\Url\Url('http://www.example.com');
        
        $this->assertTrue($url1->getHost()->isEquivalentTo($url2->getHost()));
        $this->assertFalse($url1->getHost()->isEquivalentTo($url3->getHost()));
        
        $this->assertTrue($url1->getHost()->isEquivalentTo(
                $url3->getHost(),
                array('www')
        ));
        
        $this->assertTrue($url3->getHost()->isEquivalentTo(
                $url1 ->getHost(),
                array('www')
        ));        
    }
}