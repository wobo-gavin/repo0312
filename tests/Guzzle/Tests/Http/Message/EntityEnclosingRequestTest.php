<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Message;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class EntityEnclosingRequestTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequest::__toString
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequest::addPostFields
     */
    public function testRequestIncludesBodyInMessage()
    {
        $request = RequestFactory::put('http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */-project.com/', null, 'data');
        $this->assertEquals("PUT / HTTP/1.1\r\nUser-Agent: " . /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getDefaultUserAgent() . "\r\nHost: www./* Replaced /* Replaced /* Replaced guzzle */ */ */-project.com\r\nContent-Length: 4\r\nExpect: 100-Continue\r\n\r\ndata", (string) $request);
        unset($request);

        // Adds POST fields and sets Content-Length
        $request = RequestFactory::post('http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */-project.com/', null, array(
            'data' => '123'
        ));
        $this->assertEquals("POST / HTTP/1.1\r\nUser-Agent: " . /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getDefaultUserAgent() . "\r\nHost: www./* Replaced /* Replaced /* Replaced guzzle */ */ */-project.com\r\nContent-Type: application/x-www-form-urlencoded\r\n\r\ndata=123", (string) $request);
        unset($request);

        $request = RequestFactory::post('http://www.test.com/');
        $request->addPostFiles(array(
            'file' => __FILE__
        ));
        $request->addPostFields(array(
            'a' => 'b'
        ));

        $message = (string) $request;
        $this->assertEquals('multipart/form-data', $request->getHeader('Content-Type'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequest::update
     */
    public function testRequestDeterminesContentBeforeSending()
    {
        // Tests using a POST entity body
        $request = RequestFactory::create('POST', 'http://www.test.com/');
        $request->addPostFields(array(
            'test' => '123'
        ));
        $this->assertContains("\r\n\r\ntest=123", (string)$request);
        unset($request);

        // Tests using a Content-Length entity body
        $request = RequestFactory::create('PUT', 'http://www.test.com/');
        $request->setBody(EntityBody::factory('test'));
        $this->assertNull($request->getHeader('Content-Length'));
        $request->getEventManager()->notify('request.prepare_entity_body');
        $this->assertEquals(4, $request->getHeader('Content-Length'));
        unset($request);

        // Tests using a Transfer-Encoding chunked entity body already set
        $request = RequestFactory::create('PUT', 'http://www.test.com/');
        $request->setBody(EntityBody::factory('test'))
                ->setHeader('Transfer-Encoding', 'chunked');
        $request->getEventManager()->notify('request.prepare_entity_body');
        $this->assertNull($request->getHeader('Content-Length'));

        // Tests using a Transfer-Encoding chunked entity body with undeterminable size
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 4\r\n\r\nData");
        $request = RequestFactory::create('PUT', 'http://www.test.com/');
        $request->setBody(EntityBody::factory(fopen($this->getServer()->getUrl(), 'r')));
        $request->getEventManager()->notify('request.prepare_entity_body');
        $this->assertNull($request->getHeader('Content-Length'));
        $this->assertEquals('chunked', $request->getHeader('Transfer-Encoding'));

        // Tests making sure HTTP/1.0 has a Content-Length header
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 4\r\n\r\nData");
        $request = RequestFactory::create('PUT', 'http://www.test.com/');
        $request->setBody(EntityBody::factory(fopen($this->getServer()->getUrl(), 'r')));
        $request->setProtocolVersion('1.0');
        try {
            $request->getEventManager()->notify('request.prepare_entity_body');
            $this->fail('Expected exception due to 1.0 and no Content-Length');
        } catch (RequestException $e) {
        }
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequest::setBody
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequest::getBody
     */
    public function testRequestHasMutableBody()
    {
        $request = RequestFactory::create('PUT', 'http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */-project.com/', null, 'data');
        $body = $request->getBody();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\EntityBody', $body);
        $this->assertEquals($body, $request->getBody());

        $request->setBody(EntityBody::factory('foobar'));
        $this->assertNotEquals($body, $request->getBody());
        $this->assertEquals('foobar', (string) $request->getBody());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequest::addPostFields
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequest::getPostFields
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequest::getPostFiles
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequest::addEvent
     */
    public function testSetPostFields()
    {
        $request = RequestFactory::create('POST', 'http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */-project.com/');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\QueryString', $request->getPostFields());

        $fields = new QueryString(array(
            'a' => 'b'
        ));
        $request->addPostFields($fields);
        $this->assertEquals($fields->getAll(), $request->getPostFields()->getAll());
        $this->assertEquals(array(), $request->getPostFiles());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequest::getPostFiles
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequest::addPostFiles
     */
    public function testSetPostFiles()
    {
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n");
        $request = RequestFactory::create('POST', $this->getServer()->getUrl());
        $request->addPostFiles(array(__FILE__));
        $this->assertEquals(array(
            'file' => '@' . __FILE__
        ), $request->getPostFields()->getAll());
        $this->assertEquals(array(
            'file' => __FILE__
        ), $request->getPostFiles());
        $request->send();
        
        $this->assertNotNull($request->getHeader('Content-Length'));
        $this->assertContains('multipart/form-data; boundary=--', $request->getHeader('Content-Type'), '-> cURL must add the boundary');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequest::addPostFiles
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestException
     */
    public function testSetPostFilesThrowsExceptionWhenFileIsNotFound()
    {
        $request = RequestFactory::create('POST', 'http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */-project.com/');
        $request->addPostFiles(array(
            'file' => 'filenotfound.ini'
        ));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequest::update
     */
    public function testProcessMethodAddsPostBodyAndEntityBodyHeaders()
    {
        $request = RequestFactory::create('POST', 'http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */-project.com/');
        $request->getPostFields()->set('a', 'b');
        $this->assertContains("\r\n\r\na=b", (string) $request);
        $this->assertEquals('application/x-www-form-urlencoded', $request->getHeader('Content-Type'));
        unset($request);

        $request = RequestFactory::create('POST', 'http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */-project.com/');
        $request->getPostFields()->set('a', 'b');
        $request->getEventManager()->notify('request.prepare_entity_body');
        $this->assertEquals('application/x-www-form-urlencoded', $request->getHeader('Content-Type'));
        $this->assertEquals('a=b', $request->getCurlOptions()->get(CURLOPT_POSTFIELDS));
        unset($request);

        $request = RequestFactory::create('POST', 'http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */-project.com/');
        $request->addPostFiles(array('file' => __FILE__));
        $request->getEventManager()->notify('request.prepare_entity_body');
        $this->assertEquals('multipart/form-data', $request->getHeader('Content-Type'));
        $this->assertEquals(array('file' => '@' . __FILE__), $request->getCurlOptions()->get(CURLOPT_POSTFIELDS));
    }
}