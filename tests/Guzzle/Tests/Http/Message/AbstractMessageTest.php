<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Message;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class AbstractMessageTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @var Request Request object
     */
    private $request;

    /**
     * Setup
     */
    public function setUp()
    {
        parent::setUp();
        
        $this->request = new Request('GET', 'http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */-project.com/');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\AbstractMessage::getParams
     */
    public function testGetParams()
    {
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Common\\Collection', $this->request->getParams());
    }
    
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\AbstractMessage::addHeaders
     */
    public function testAddHeaders()
    {
        $this->request->setHeader('A', 'B');

        $this->assertEquals($this->request, $this->request->addHeaders(array(
            'X-Data' => '123'
        )));

        $this->assertTrue($this->request->hasHeader('X-Data') !== false);
        $this->assertTrue($this->request->hasHeader('A') !== false);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\AbstractMessage::getHeaders
     */
    public function testGetHeader()
    {
        $this->request->setHeader('Test', '123');
        $this->assertEquals('123', $this->request->getHeader('Test'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\AbstractMessage::getHeaders
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\AbstractMessage::setHeaders
     */
    public function testGetHeaders()
    {
        $this->assertEquals($this->request, $this->request->setHeaders(array(
            'a' => 'b',
            'c' => 'd'
        )));

        $this->assertEquals(array(
            'a' => 'b',
            'c' => 'd'
        ), $this->request->getHeaders()->getAll());

        $this->assertEquals(array(
            'a' => 'b'
        ), $this->request->getHeaders(array('a'))->getAll());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\AbstractMessage::hasHeader
     */
    public function testHasHeader()
    {
        $this->assertFalse($this->request->hasHeader('Foo'));
        $this->request->setHeader('Foo', 'Bar');
        $this->assertEquals('Foo', $this->request->hasHeader('Foo'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\AbstractMessage::removeHeader
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\AbstractMessage::setHeader
     */
    public function testRemoveHeader()
    {
        $this->request->setHeader('Foo', 'Bar');
        $this->assertEquals('Foo', $this->request->hasHeader('Foo'));
        $this->request->removeHeader('Foo');
        $this->assertFalse($this->request->hasHeader('Foo'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\AbstractMessage
     */
    public function testHoldsCacheControlDirectives()
    {
        $r = $this->request;

        // Set a directive using a header
        $r->setHeader('Cache-Control', 'max-age=100');
        $this->assertEquals(100, $r->getCacheControlDirective('max-age'));

        // Set a header using the directive and check that the header was updated
        $this->assertSame($r, $r->addCacheControlDirective('max-age', 80));
        $this->assertEquals(80, $r->getCacheControlDirective('max-age'));
        $this->assertEquals('max-age=80', $r->getHeader('Cache-Control'));

        // Remove the directive
        $this->assertEquals($r, $r->removeCacheControlDirective('max-age'));
        $this->assertEquals('', $r->getHeader('Cache-Control'));
        $this->assertEquals(null, $r->getCacheControlDirective('max-age'));
        // Remove a non-existent directive
        $this->assertEquals($r, $r->removeCacheControlDirective('max-age'));

        // Has directive
        $this->assertFalse($r->hasCacheControlDirective('max-age'));
        $r->addCacheControlDirective('must-revalidate');
        $this->assertTrue($r->hasCacheControlDirective('must-revalidate'));
    }
}