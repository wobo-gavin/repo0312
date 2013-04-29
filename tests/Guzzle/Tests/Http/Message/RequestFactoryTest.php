<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Message;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\QueryString;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser\Message\MessageParser;

/**
 * @group server
 */
class HttpRequestFactoryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory::getInstance
     */
    public function testCachesSingletonInstance()
    {
        $factory = RequestFactory::getInstance();
        $this->assertSame($factory, RequestFactory::getInstance());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory::create
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory::getInstance
     */
    public function testCreatesNewGetRequests()
    {
        $request = RequestFactory::getInstance()->create('GET', 'http://www.google.com/');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Message\\MessageInterface', $request);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Message\\RequestInterface', $request);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Message\\Request', $request);
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('http', $request->getScheme());
        $this->assertEquals('http://www.google.com/', $request->getUrl());
        $this->assertEquals('www.google.com', $request->getHost());
        $this->assertEquals('/', $request->getPath());
        $this->assertEquals('/', $request->getResource());

        // Create a GET request with a custom receiving body
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n");
        $b = EntityBody::factory();
        $request = RequestFactory::getInstance()->create('GET', $this->getServer()->getUrl(), null, $b);
        $request->setClient(new Client());
        $response = $request->send();
        $this->assertSame($b, $response->getBody());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory::create
     */
    public function testCreatesPutRequests()
    {
        // Test using a string
        $request = RequestFactory::getInstance()->create('PUT', 'http://www.google.com/path?q=1&v=2', null, 'Data');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Message\\EntityEnclosingRequest', $request);
        $this->assertEquals('PUT', $request->getMethod());
        $this->assertEquals('http', $request->getScheme());
        $this->assertEquals('http://www.google.com/path?q=1&v=2', $request->getUrl());
        $this->assertEquals('www.google.com', $request->getHost());
        $this->assertEquals('/path', $request->getPath());
        $this->assertEquals('/path?q=1&v=2', $request->getResource());
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\EntityBody', $request->getBody());
        $this->assertEquals('Data', (string) $request->getBody());
        unset($request);

        // Test using an EntityBody
        $request = RequestFactory::getInstance()->create('PUT', 'http://www.google.com/path?q=1&v=2', null, EntityBody::factory('Data'));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Message\\EntityEnclosingRequest', $request);
        $this->assertEquals('Data', (string) $request->getBody());

        // Test using a resource
        $resource = fopen('php://temp', 'w+');
        fwrite($resource, 'Data');
        $request = RequestFactory::getInstance()->create('PUT', 'http://www.google.com/path?q=1&v=2', null, $resource);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Message\\EntityEnclosingRequest', $request);
        $this->assertEquals('Data', (string) $request->getBody());

        // Test using an object that can be cast as a string
        $request = RequestFactory::getInstance()->create('PUT', 'http://www.google.com/path?q=1&v=2', null, Url::factory('http://www.example.com/'));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Message\\EntityEnclosingRequest', $request);
        $this->assertEquals('http://www.example.com/', (string) $request->getBody());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory::create
     */
    public function testCreatesHeadAndDeleteRequests()
    {
        $request = RequestFactory::getInstance()->create('DELETE', 'http://www.test.com/');
        $this->assertEquals('DELETE', $request->getMethod());
        $request = RequestFactory::getInstance()->create('HEAD', 'http://www.test.com/');
        $this->assertEquals('HEAD', $request->getMethod());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory::create
     */
    public function testCreatesOptionsRequests()
    {
        $request = RequestFactory::getInstance()->create('OPTIONS', 'http://www.example.com/');
        $this->assertEquals('OPTIONS', $request->getMethod());
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Message\\Request', $request);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory::create
     */
    public function testCreatesNewPutRequestWithBody()
    {
        $request = RequestFactory::getInstance()->create('PUT', 'http://www.google.com/path?q=1&v=2', null, 'Data');
        $this->assertEquals('Data', (string) $request->getBody());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory::create
     */
    public function testCreatesNewPostRequestWithFields()
    {
        // Use an array
        $request = RequestFactory::getInstance()->create('POST', 'http://www.google.com/path?q=1&v=2', null, array(
            'a' => 'b'
        ));
        $this->assertEquals(array('a' => 'b'), $request->getPostFields()->getAll());
        unset($request);

        // Use a collection
        $request = RequestFactory::getInstance()->create('POST', 'http://www.google.com/path?q=1&v=2', null, new Collection(array(
            'a' => 'b'
        )));
        $this->assertEquals(array('a' => 'b'), $request->getPostFields()->getAll());

        // Use a QueryString
        $request = RequestFactory::getInstance()->create('POST', 'http://www.google.com/path?q=1&v=2', null, new QueryString(array(
            'a' => 'b'
        )));
        $this->assertEquals(array('a' => 'b'), $request->getPostFields()->getAll());

        $request = RequestFactory::getInstance()->create('POST', 'http://www.test.com/', null, array(
            'a' => 'b',
            'file' => '@' . __FILE__
        ));

        $this->assertEquals(array(
            'a' => 'b'
        ), $request->getPostFields()->getAll());

        $files = $request->getPostFiles();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\PostFile', $files['file'][0]);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory::fromParts
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory::create
     */
    public function testCreatesFromParts()
    {
        $parts = parse_url('http://michael:123@www.google.com:8080/path?q=1&v=2');

        $request = RequestFactory::getInstance()->fromParts('PUT', $parts, null, 'Data');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Message\\EntityEnclosingRequest', $request);
        $this->assertEquals('PUT', $request->getMethod());
        $this->assertEquals('http', $request->getScheme());
        $this->assertEquals('http://www.google.com:8080/path?q=1&v=2', $request->getUrl());
        $this->assertEquals('www.google.com', $request->getHost());
        $this->assertEquals('www.google.com:8080', $request->getHeader('Host'));
        $this->assertEquals('/path', $request->getPath());
        $this->assertEquals('/path?q=1&v=2', $request->getResource());
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\EntityBody', $request->getBody());
        $this->assertEquals('Data', (string)$request->getBody());
        $this->assertEquals('michael', $request->getUsername());
        $this->assertEquals('123', $request->getPassword());
        $this->assertEquals('8080', $request->getPort());
        $this->assertEquals(array(
            'scheme' => 'http',
            'host' => 'www.google.com',
            'port' => 8080,
            'path' => '/path',
            'query' => 'q=1&v=2',
        ), parse_url($request->getUrl()));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory::fromMessage
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory::create
     */
    public function testCreatesFromMessage()
    {
        $auth = base64_encode('michael:123');
        $message = "PUT /path?q=1&v=2 HTTP/1.1\r\nHost: www.google.com:8080\r\nContent-Length: 4\r\nAuthorization: Basic {$auth}\r\n\r\nData";
        $request = RequestFactory::getInstance()->fromMessage($message);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Message\\EntityEnclosingRequest', $request);
        $this->assertEquals('PUT', $request->getMethod());
        $this->assertEquals('http', $request->getScheme());
        $this->assertEquals('http://www.google.com:8080/path?q=1&v=2', $request->getUrl());
        $this->assertEquals('www.google.com', $request->getHost());
        $this->assertEquals('www.google.com:8080', $request->getHeader('Host'));
        $this->assertEquals('/path', $request->getPath());
        $this->assertEquals('/path?q=1&v=2', $request->getResource());
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\EntityBody', $request->getBody());
        $this->assertEquals('Data', (string) $request->getBody());
        $this->assertEquals('michael', $request->getUsername());
        $this->assertEquals('123', $request->getPassword());
        $this->assertEquals('8080', $request->getPort());

        // Test passing a blank message returns false
        $this->assertFalse($request = RequestFactory::getInstance()->fromMessage(''));

        // Test passing a url with no port
        $message = "PUT /path?q=1&v=2 HTTP/1.1\r\nHost: www.google.com\r\nContent-Length: 4\r\nAuthorization: Basic {$auth}\r\n\r\nData";
        $request = RequestFactory::getInstance()->fromMessage($message);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Message\\EntityEnclosingRequest', $request);
        $this->assertEquals('PUT', $request->getMethod());
        $this->assertEquals('http', $request->getScheme());
        $this->assertEquals('http://www.google.com/path?q=1&v=2', $request->getUrl());
        $this->assertEquals('www.google.com', $request->getHost());
        $this->assertEquals('/path', $request->getPath());
        $this->assertEquals('/path?q=1&v=2', $request->getResource());
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\EntityBody', $request->getBody());
        $this->assertEquals('Data', (string)$request->getBody());
        $this->assertEquals('michael', $request->getUsername());
        $this->assertEquals('123', $request->getPassword());
        $this->assertEquals(80, $request->getPort());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory::create
     */
    public function testCreatesNewTraceRequest()
    {
        $request = RequestFactory::getInstance()->create('TRACE', 'http://www.google.com/');
        $this->assertFalse($request instanceof \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequest);
        $this->assertEquals('TRACE', $request->getMethod());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory::create
     */
    public function testCreatesProperTransferEncodingRequests()
    {
        $request = RequestFactory::getInstance()->create('PUT', 'http://www.google.com/', array(
            'Transfer-Encoding' => 'chunked'
        ), 'hello');
        $this->assertEquals('chunked', $request->getHeader('Transfer-Encoding'));
        $this->assertFalse($request->hasHeader('Content-Length'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory::fromMessage
     */
    public function testProperlyDealsWithDuplicateHeaders()
    {
        $parser = new MessageParser();

        $message = "POST / http/1.1\r\n"
            . "DATE:Mon, 09 Sep 2011 23:36:00 GMT\r\n"
            . "host:host.foo.com\r\n"
            . "ZOO:abc\r\n"
            . "ZOO:123\r\n"
            . "ZOO:HI\r\n"
            . "zoo:456\r\n\r\n";

        $parts = $parser->parseRequest($message);
        $this->assertEquals(array (
            'DATE' => 'Mon, 09 Sep 2011 23:36:00 GMT',
            'host' => 'host.foo.com',
            'ZOO'  => array('abc', '123', 'HI'),
            'zoo'  => '456',
        ), $parts['headers']);

        $request = RequestFactory::getInstance()->fromMessage($message);

        $this->assertEquals(array(
            'ZOO' => array('abc', '123', 'HI'),
            'zoo' => array('456')
        ), $request->getHeader('zoo')->raw());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory::fromMessage
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory::create
     */
    public function testCreatesHttpMessagesWithBodiesAndNormalizesLineEndings()
    {
        $message = "POST / http/1.1\r\n"
                 . "Content-Type:application/x-www-form-urlencoded; charset=utf8\r\n"
                 . "Date:Mon, 09 Sep 2011 23:36:00 GMT\r\n"
                 . "Host:host.foo.com\r\n\r\n"
                 . "foo=bar";

        $request = RequestFactory::getInstance()->fromMessage($message);
        $this->assertEquals('application/x-www-form-urlencoded; charset=utf8', (string) $request->getHeader('Content-Type'));
        $this->assertEquals('foo=bar', (string) $request->getBody());

        $message = "POST / http/1.1\n"
                 . "Content-Type:application/x-www-form-urlencoded; charset=utf8\n"
                 . "Date:Mon, 09 Sep 2011 23:36:00 GMT\n"
                 . "Host:host.foo.com\n\n"
                 . "foo=bar";
        $request = RequestFactory::getInstance()->fromMessage($message);
        $this->assertEquals('foo=bar', (string) $request->getBody());

        $message = "PUT / HTTP/1.1\r\nContent-Length: 0\r\n\r\n";
        $request = RequestFactory::getInstance()->fromMessage($message);
        $this->assertTrue($request->hasHeader('Content-Length'));
        $this->assertEquals(0, (string) $request->getHeader('Content-Length'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory::create
     */
    public function testHandlesChunkedTransferEncoding()
    {
        $request = RequestFactory::getInstance()->create('PUT', 'http://www.foo.com/', array(
            'Transfer-Encoding' => 'chunked'
        ), 'Test');
        $this->assertFalse($request->hasHeader('Content-Length'));
        $this->assertEquals('chunked', $request->getHeader('Transfer-Encoding'));

        $request = RequestFactory::getInstance()->create('POST', 'http://www.foo.com/', array(
            'transfer-encoding' => 'chunked'
        ), array(
            'foo' => 'bar'
        ));

        $this->assertFalse($request->hasHeader('Content-Length'));
        $this->assertEquals('chunked', $request->getHeader('Transfer-Encoding'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory::cloneRequestWithMethod
     */
    public function testClonesRequestsWithMethodWithoutClient()
    {
        $f = RequestFactory::getInstance();
        $request = $f->create('GET', 'http://www.test.com', array('X-Foo' => 'Bar'));
        $request->getParams()->replace(array(
            'curl_handle' => 'foo',
            'curl_multi' => 'bar',
            'test' => '123'
        ));
        $request->getCurlOptions()->set('foo', 'bar');
        $cloned = $f->cloneRequestWithMethod($request, 'PUT');
        $this->assertEquals('PUT', $cloned->getMethod());
        $this->assertEquals('Bar', (string) $cloned->getHeader('X-Foo'));
        $this->assertEquals('http://www.test.com', $cloned->getUrl());
        // Ensure params are cloned and cleaned up
        $this->assertEquals(1, count($cloned->getParams()->getAll()));
        $this->assertEquals('123', $cloned->getParams()->get('test'));
        // Ensure curl options are cloned
        $this->assertEquals('bar', $cloned->getCurlOptions()->get('foo'));
        // Ensure event dispatcher is cloned
        $this->assertNotSame($request->getEventDispatcher(), $cloned->getEventDispatcher());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory::cloneRequestWithMethod
     */
    public function testClonesRequestsWithMethodWithClient()
    {
        $f = RequestFactory::getInstance();
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->put('http://www.test.com', array('Content-Length' => 4), 'test');
        $cloned = $f->cloneRequestWithMethod($request, 'GET');
        $this->assertEquals('GET', $cloned->getMethod());
        $this->assertNull($cloned->getHeader('Content-Length'));
        $this->assertEquals('http://www.test.com', $cloned->getUrl());
        $this->assertSame($request->getClient(), $cloned->getClient());
    }
}
