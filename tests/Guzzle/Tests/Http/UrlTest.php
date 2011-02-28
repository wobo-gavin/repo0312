<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\HttpException;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class UrlTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @return array
     */
    public function urlDataProvider()
    {
        $resp = array();
        foreach (array(
            'http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */-project.com/',
            'http://www.google.com:8080/path?q=1&v=2',
            'https://www./* Replaced /* Replaced /* Replaced guzzle */ */ */-project.com/?value1=a&value2=b',
            'https:///* Replaced /* Replaced /* Replaced guzzle */ */ */-project.com/index.html',
            '/index.html?q=2',
            'http://www.google.com:8080/path?q=1&v=2',
            'http://michael:123@www.google.com:8080/path?q=1&v=2',
            'http://michael@test.com/abc/def?q=10#test',
        ) as $url) {
            $parts = parse_url($url);
            $resp[] = array($url, parse_url($url), !isset($parts['host']));
        }

        return $resp;
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::buildUrl
     * @dataProvider urlDataProvider
     */
    public function testBuildsUrlsFromParts($url, $parts, $throwE)
    {
        $this->assertEquals($url, Url::buildUrl($parts));
    }
    
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::getPort
     */
    public function testPortIsDeterminedFromScheme()
    {
        $this->assertEquals(80, Url::factory('http://www.test.com/')->getPort());
        $this->assertEquals(443, Url::factory('https://www.test.com/')->getPort());
        $this->assertEquals(null, Url::factory('ftp://www.test.com/')->getPort());
        $this->assertEquals(8192, Url::factory('http://www.test.com:8192/')->getPort());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::__clone
     */
    public function testCloneCreatesNewInternalObjects()
    {
        $u1 = Url::factory('http://www.test.com/');
        $u2 = clone $u1;
        $this->assertNotSame($u1->getQuery(), $u2->getQuery());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::__construct
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::factory
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::__toString
     */
    public function testValidatesUrlPartsInFactory()
    {
        try {
            Url::factory('/index.php');
            $this->fail('Should have thrown an exception because the host and scheme are missing');
        } catch (HttpException $e) {
        }

        $url = 'http://michael:test@test.com:80/path/123?q=abc#test';
        $u = Url::factory($url);
        $this->assertEquals('http://michael:test@test.com/path/123?q=abc#test', (string)$u);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url
     */
    public function testUrlStoresParts()
    {
        $url = Url::factory('http://test:pass@www.test.com:8081/path/path2/?a=1&b=2#fragment');
        $this->assertEquals('http', $url->getScheme());
        $this->assertEquals('test', $url->getUsername());
        $this->assertEquals('pass', $url->getPassword());
        $this->assertEquals('www.test.com', $url->getHost());
        $this->assertEquals(8081, $url->getPort());
        $this->assertEquals('/path/path2/', $url->getPath());
        $this->assertEquals('?a=1&b=2', (string)$url->getQuery());
        $this->assertEquals('fragment', $url->getFragment());

        $this->assertEquals(array(
            'fragment' => 'fragment',
            'host' => 'www.test.com',
            'pass' => 'pass',
            'path' => '/path/path2/',
            'port' => 8081,
            'query' => '?a=1&b=2',
            'query_prefix' => '?',
            'scheme' => 'http',
            'user' => 'test'
        ), $url->getParts());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::setPath
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::getPath
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::getPathSegments
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::buildUrl
     */
    public function testHandlesPathsCorrectly()
    {
        $url = Url::factory('http://www.test.com');
        $this->assertEquals('/', $url->getPath());
        $url->setPath('test');
        $this->assertEquals('/test', $url->getPath());

        $url->setPath('/test/123/abc');
        $this->assertEquals(array('test', '123', 'abc'), $url->getPathSegments());

        $parts = parse_url('http://www.test.com/test');
        $parts['path'] = '';
        $this->assertEquals('http://www.test.com/', Url::buildUrl($parts));
        $parts['path'] = 'test';
        $this->assertEquals('http://www.test.com/test', Url::buildUrl($parts));
    }
}