<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Message;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Header;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\AbstractMessage
 */
class AbstractMessageTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /** @var Request Request object */
    private $request;

    /** @var AbstractMessage */
    private $mock;

    public function setUp()
    {
        parent::setUp();
        $this->mock = $this->getMockForAbstractClass('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\AbstractMessage');
    }

    public function tearDown()
    {
        $this->mock = $this->request = null;
    }

    public function testGetParams()
    {
        $request = new Request('GET', 'http://example.com');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Common\\Collection', $request->getParams());
    }

    public function testAddHeaders()
    {
        $this->mock->setHeader('A', 'B');

        $this->assertEquals($this->mock, $this->mock->addHeaders(array(
            'X-Data' => '123'
        )));

        $this->assertTrue($this->mock->hasHeader('X-Data') !== false);
        $this->assertTrue($this->mock->hasHeader('A') !== false);
    }

    public function testAllowsHeaderToSetAsHeader()
    {
        $h = new Header('A', 'B');
        $this->mock->setHeader('A', $h);
        $this->assertSame($h, $this->mock->getHeader('A'));
    }

    public function testGetHeader()
    {
        $this->mock->setHeader('Test', '123');
        $this->assertEquals('123', $this->mock->getHeader('Test'));
    }

    public function testGetHeaders()
    {
        $this->assertSame($this->mock, $this->mock->setHeaders(array('a' => 'b', 'c' => 'd')));
        $h = $this->mock->getHeaders();
        $this->assertArrayHasKey('a', $h->toArray());
        $this->assertArrayHasKey('c', $h->toArray());
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Header\HeaderInterface', $h->get('a'));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Header\HeaderInterface', $h->get('c'));
    }

    public function testGetHeaderLinesUsesGlue()
    {
        $this->mock->setHeaders(array('a' => 'b', 'c' => 'd'));
        $this->mock->addHeader('a', 'e');
        $this->mock->getHeader('a')->setGlue('!');
        $this->assertEquals(array(
            'a: b! e',
            'c: d'
        ), $this->mock->getHeaderLines());
    }

    public function testHasHeader()
    {
        $this->assertFalse($this->mock->hasHeader('Foo'));
        $this->mock->setHeader('Foo', 'Bar');
        $this->assertEquals(true, $this->mock->hasHeader('Foo'));
        $this->mock->setHeader('foo', 'yoo');
        $this->assertEquals(true, $this->mock->hasHeader('Foo'));
        $this->assertEquals(true, $this->mock->hasHeader('foo'));
        $this->assertEquals(false, $this->mock->hasHeader('bar'));
    }

    public function testRemoveHeader()
    {
        $this->mock->setHeader('Foo', 'Bar');
        $this->assertEquals(true, $this->mock->hasHeader('Foo'));
        $this->mock->removeHeader('Foo');
        $this->assertFalse($this->mock->hasHeader('Foo'));
    }

    public function testHoldsCacheControlDirectives()
    {
        $mock = $this->mock;

        // Set a directive using a header
        $mock->setHeader('Cache-Control', 'max-age=100');
        $this->assertEquals(100, $mock->getCacheControlDirective('max-age'));

        // Set a header using the directive and check that the header was updated
        $this->assertSame($mock, $mock->addCacheControlDirective('max-age', 80));
        $this->assertEquals(80, $mock->getCacheControlDirective('max-age'));
        $this->assertEquals('max-age=80', $mock->getHeader('Cache-Control'));

        // Remove the directive
        $this->assertEquals($mock, $mock->removeCacheControlDirective('max-age'));
        $this->assertEquals('', $mock->getHeader('Cache-Control'));
        $this->assertEquals(null, $mock->getCacheControlDirective('max-age'));
        // Remove a non-existent directive
        $this->assertEquals($mock, $mock->removeCacheControlDirective('max-age'));

        // Has directive
        $this->assertFalse($mock->hasCacheControlDirective('max-age'));
        $mock->addCacheControlDirective('must-revalidate');
        $this->assertTrue($mock->hasCacheControlDirective('must-revalidate'));

        // Make sure that it works with multiple Cache-Control headers
        $mock->setHeader('Cache-Control', 'must-revalidate, max-age=100');
        $mock->addHeaders(array(
            'Cache-Control' => 'no-cache'
        ));

        $this->assertEquals(true, $mock->getCacheControlDirective('no-cache'));
        $this->assertEquals(true, $mock->getCacheControlDirective('must-revalidate'));
        $this->assertEquals(100, $mock->getCacheControlDirective('max-age'));
    }

    /**
     * In some rare cases. Like with the Twitter Search API:
     *
     *    http://search.twitter.com/search.json?q=from:mtdowling
     *
     * There is more than once Cache-Control added.
     * Twice the max-age for example.
     */
    public function testDoubleCacheControlDirectives()
    {
        $mock = $this->mock;
        $mock->setHeader('Cache-Control', 'max-age=15, must-revalidate, max-age=300');
        $this->assertEquals(300, $mock->getCacheControlDirective('max-age'));
    }

    public function testReturnsNullWhenHeaderIsNotFound()
    {
        $this->assertNull($this->mock->getHeader('foo'));
    }

    public function testAddingHeadersPreservesOriginalHeaderCase()
    {
        $this->mock->addHeaders(array(
            'test' => '123',
            'Test' => 'abc'
        ));
        $this->mock->addHeader('test', '456');
        $this->mock->addHeader('test', '789');

        $header = $this->mock->getHeader('test');
        $this->assertContains('123', $header->toArray());
        $this->assertContains('456', $header->toArray());
        $this->assertContains('789', $header->toArray());
        $this->assertContains('abc', $header->toArray());
    }

    public function testCanStoreEmptyHeaders()
    {
        $this->mock->setHeader('Content-Length', 0);
        $this->assertTrue($this->mock->hasHeader('Content-Length'));
        $this->assertEquals(0, (string) $this->mock->getHeader('Content-Length'));
    }

    public function testCanSetCustomHeaderFactory()
    {
        $f = new Header\HeaderFactory();
        $this->mock->setHeaderFactory($f);
        $this->assertSame($f, $this->readAttribute($this->mock, 'headerFactory'));
    }
}
