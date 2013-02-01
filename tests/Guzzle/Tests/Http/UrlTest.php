<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url;

class UrlTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     *
     */
    public function testEmptyUrl()
    {
        $url = Url::factory("");
        $this->assertEquals("", (string) $url);
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
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::isAbsolute
     */
    public function testValidatesUrlPartsInFactory()
    {
        $url = Url::factory('/index.php');
        $this->assertEquals('/index.php', (string) $url);
        $this->assertFalse($url->isAbsolute());

        $url = 'http://michael:test@test.com:80/path/123?q=abc#test';
        $u = Url::factory($url);
        $this->assertEquals('http://michael:test@test.com/path/123?q=abc#test', (string) $u);
        $this->assertTrue($u->isAbsolute());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::factory
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::getHost
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::getPort
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::getQuery
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::getPath
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::getFragment
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::__toString
     */
    public function testAllowsFalsyUrlParts()
    {
        $url = Url::factory('http://0:50/0?0#0');
        $this->assertSame('0', $url->getHost());
        $this->assertEquals(50, $url->getPort());
        $this->assertSame('/0', $url->getPath());
        $this->assertEquals('0=', (string) $url->getQuery());
        $this->assertSame('0', $url->getFragment());
        $this->assertEquals('http://0:50/0?0=#0', (string) $url);

        $url = Url::factory('');
        $this->assertSame('', (string) $url);

        $url = Url::factory('0');
        $this->assertSame('0', (string) $url);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::buildUrl
     */
    public function testBuildsRelativeUrlsWithFalsyParts()
    {
        $url = Url::buildUrl(array(
                'host' => '0',
                'path' => '0',
            ));

        $this->assertSame('//0/0', $url);

        $url = Url::buildUrl(array(
                'path' => '0',
            ));
        $this->assertSame('0', $url);
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
        $this->assertEquals('fragment', $url->getFragment());
        $this->assertEquals('a=1&b=2', (string) $url->getQuery());

        $this->assertEquals(array(
            'fragment' => 'fragment',
            'host' => 'www.test.com',
            'pass' => 'pass',
            'path' => '/path/path2/',
            'port' => 8081,
            'query' => 'a=1&b=2',
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
        $this->assertEquals('', $url->getPath());
        $url->setPath('test');
        $this->assertEquals('test', $url->getPath());

        $url->setPath('/test/123/abc');
        $this->assertEquals(array('test', '123', 'abc'), $url->getPathSegments());

        $parts = parse_url('http://www.test.com/test');
        $parts['path'] = '';
        $this->assertEquals('http://www.test.com', Url::buildUrl($parts));
        $parts['path'] = 'test';
        $this->assertEquals('http://www.test.com/test', Url::buildUrl($parts));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::buildUrl
     */
    public function testAddsQueryStringIfPresent()
    {
        $this->assertEquals('?foo=bar', Url::buildUrl(array(
            'query' => 'foo=bar'
        )));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::addPath
     */
    public function testAddsToPath()
    {
        // Does nothing here
        $this->assertEquals('http://e.com/base?a=1', (string) Url::factory('http://e.com/base?a=1')->addPath(false));
        $this->assertEquals('http://e.com/base?a=1', (string) Url::factory('http://e.com/base?a=1')->addPath(''));
        $this->assertEquals('http://e.com/base?a=1', (string) Url::factory('http://e.com/base?a=1')->addPath('/'));

        $this->assertEquals('http://e.com/base/relative?a=1', (string) Url::factory('http://e.com/base?a=1')->addPath('relative'));
        $this->assertEquals('http://e.com/base/relative?a=1', (string) Url::factory('http://e.com/base?a=1')->addPath('/relative'));
    }

    /**
     * URL combination data provider
     *
     * @return array
     */
    public function urlCombineDataProvider()
    {
        return array(
            array('http://www.example.com/', 'http://www.example.com/', 'http://www.example.com/'),
            array('http://www.example.com/path', '/absolute', 'http://www.example.com/absolute'),
            array('http://www.example.com/path', '/absolute?q=2', 'http://www.example.com/absolute?q=2'),
            array('http://www.example.com/path', 'more', 'http://www.example.com/path/more'),
            array('http://www.example.com/path', 'more?q=1', 'http://www.example.com/path/more?q=1'),
            array('http://www.example.com/', '?q=1', 'http://www.example.com/?q=1'),
            array('http://www.example.com/path', 'http://test.com', 'http://test.com/path'),
            array('http://www.example.com:8080/path', 'http://test.com', 'http://test.com/path'),
            array('http://www.example.com:8080/path', '?q=2#abc', 'http://www.example.com:8080/path?q=2#abc'),
            array('http://u:a@www.example.com/path', 'test', 'http://u:a@www.example.com/path/test'),
            array('http://www.example.com/path', 'http://u:a@www.example.com/', 'http://u:a@www.example.com/path'),
            array('/path?q=2', 'http://www.test.com/', 'http://www.test.com/path?q=2'),
        );
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::combine
     * @dataProvider urlCombineDataProvider
     */
    public function testCombinesUrls($a, $b, $c)
    {
        $this->assertEquals($c, (string) Url::factory($a)->combine($b));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url
     */
    public function testHasGettersAndSetters()
    {
        $url = Url::factory('http://www.test.com/');
        $this->assertEquals('example.com', $url->setHost('example.com')->getHost());
        $this->assertEquals('8080', $url->setPort(8080)->getPort());
        $this->assertEquals('/foo/bar', $url->setPath(array('foo', 'bar'))->getPath());
        $this->assertEquals('a', $url->setPassword('a')->getPassword());
        $this->assertEquals('b', $url->setUsername('b')->getUsername());
        $this->assertEquals('abc', $url->setFragment('abc')->getFragment());
        $this->assertEquals('https', $url->setScheme('https')->getScheme());
        $this->assertEquals('a=123', (string) $url->setQuery('a=123')->getQuery());
        $this->assertEquals('https://b:a@example.com:8080/foo/bar?a=123#abc', (string)$url);
        $this->assertEquals('b=boo', (string) $url->setQuery(new QueryString(array(
            'b' => 'boo'
        )))->getQuery());
        $this->assertEquals('https://b:a@example.com:8080/foo/bar?b=boo#abc', (string)$url);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::setQuery
     */
    public function testSetQueryAcceptsArray()
    {
        $url = Url::factory('http://www.test.com');
        $url->setQuery(array('a' => 'b'));
        $this->assertEquals('http://www.test.com?a=b', (string) $url);
    }

    public function urlProvider()
    {
        return array(
            array('/foo/..', ''),
            array('//foo//..', ''),
            array('/foo/../..', ''),
            array('/foo/../.', ''),
            array('/./foo/..', ''),
            array('/./foo', 'foo'),
            array('/./foo/', 'foo/'),
            array('/./foo/bar/baz/pho/../..', 'foo/bar'),
            array('*', '*')
        );
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::normalizePath
     * @dataProvider urlProvider
     */
    public function testNormalizesPaths($path, $result)
    {
        $url = Url::factory('http://www.example.com/');
        $url->setPath($path)->normalizePath();
        $this->assertEquals($result, $url->getPath());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url::setHost
     */
    public function testSettingHostWithPortModifiesPort()
    {
        $url = Url::factory('http://www.example.com');
        $url->setHost('foo:8983');
        $this->assertEquals('foo', $url->getHost());
        $this->assertEquals(8983, $url->getPort());
    }
}
