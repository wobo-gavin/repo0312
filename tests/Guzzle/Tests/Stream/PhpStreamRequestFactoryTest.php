<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Stream;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\Stream;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\PhpStreamRequestFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;

/**
 * @group server
 * @covers \Gizzle\Stream\PhpStreamRequestFactory
 */
class PhpStreamRequestFactoryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @var Client
     */
    protected $/* Replaced /* Replaced /* Replaced client */ */ */;

    /**
     * @var PhpStreamRequestFactory
     */
    protected $factory;

    protected function setUp()
    {
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $this->factory = new PhpStreamRequestFactory();
    }

    public function testOpensValidStreamByCreatingContext()
    {
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 2\r\n\r\nhi");
        $request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->get('/');
        $stream = $this->factory->fromRequest($request);
        $this->assertEquals('hi', (string) $stream);
        $this->assertEquals(array(
            'HTTP/1.1 200 OK',
            'Content-Length: 2',
            'Connection: close'
        ), $this->factory->getLastResponseHeaders());
    }

    public function testOpensValidStreamByPassingContextAndMerging()
    {
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 2\r\n\r\nhi");
        $request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->get('/');
        $this->factory = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\PhpStreamRequestFactory')
            ->setMethods(array('createContext', 'createStream'))
            ->getMock();
        $this->factory->expects($this->never())
            ->method('createContext');
        $this->factory->expects($this->once())
            ->method('createStream')
            ->will($this->returnValue(new Stream(fopen('php://temp', 'r'))));

        $context = array('http' => array('method' => 'HEAD', 'ignore_errors' => false));
        $this->factory->fromRequest($request, stream_context_create($context));
        $options = stream_context_get_options($this->readAttribute($this->factory, 'context'));
        $this->assertEquals('HEAD', $options['http']['method']);
        $this->assertFalse($options['http']['ignore_errors']);
        $this->assertEquals('1.0', $options['http']['protocol_version']);
    }

    public function testAppliesProxySettings()
    {
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 2\r\n\r\nhi");
        $request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->get('/');
        $request->getCurlOptions()->set(CURLOPT_PROXY, 'tcp://foo.com');
        $this->factory = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\PhpStreamRequestFactory')
            ->setMethods(array('createStream'))
            ->getMock();
        $this->factory->expects($this->once())
            ->method('createStream')
            ->will($this->returnValue(new Stream(fopen('php://temp', 'r'))));
        $this->factory->fromRequest($request);
        $options = stream_context_get_options($this->readAttribute($this->factory, 'context'));
        $this->assertEquals('tcp://foo.com', $options['http']['proxy']);
    }

    public function testAddsPostFields()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 2\r\n\r\nhi");
        $request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->post('/', array('Foo' => 'Bar'), array('foo' => 'baz bar'));
        $stream = $this->factory->fromRequest($request);
        $this->assertEquals('hi', (string) $stream);
        $this->assertEquals(array(
            'HTTP/1.1 200 OK',
            'Content-Length: 2',
            'Connection: close'
        ), $this->factory->getLastResponseHeaders());
        $received = $this->getServer()->getReceivedRequests();
        $this->assertEquals(1, count($received));
        $this->assertContains('POST / HTTP/1.0', $received[0]);
        $this->assertContains('host: ', $received[0]);
        $this->assertContains('user-agent: /* Replaced /* Replaced /* Replaced Guzzle */ */ *//', $received[0]);
        $this->assertContains('foo: Bar', $received[0]);
        $this->assertContains('content-length: 13', $received[0]);
        $this->assertContains('foo=baz%20bar', $received[0]);
    }

    public function testAddsBody()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 2\r\n\r\nhi");
        $request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->put('/', array('Foo' => 'Bar'), 'Testing...123');
        $stream = $this->factory->fromRequest($request);
        $this->assertEquals('hi', (string) $stream);
        $this->assertEquals(array(
            'HTTP/1.1 200 OK',
            'Content-Length: 2',
            'Connection: close'
        ), $this->factory->getLastResponseHeaders());
        $received = $this->getServer()->getReceivedRequests();
        $this->assertEquals(1, count($received));
        $this->assertContains('PUT / HTTP/1.0', $received[0]);
        $this->assertContains('host: ', $received[0]);
        $this->assertContains('user-agent: /* Replaced /* Replaced /* Replaced Guzzle */ */ *//', $received[0]);
        $this->assertContains('foo: Bar', $received[0]);
        $this->assertContains('content-length: 13', $received[0]);
        $this->assertContains('Testing...123', $received[0]);
    }

    public function testCanDisableSslValidation()
    {
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 2\r\n\r\nhi");
        $request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->get('/');
        $request->getCurlOptions()->set(CURLOPT_SSL_VERIFYPEER, false);
        $this->factory = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\PhpStreamRequestFactory')
            ->setMethods(array('createStream'))
            ->getMock();
        $this->factory->expects($this->once())
            ->method('createStream')
            ->will($this->returnValue(new Stream(fopen('php://temp', 'r'))));
        $this->factory->fromRequest($request);
        $options = stream_context_get_options($this->readAttribute($this->factory, 'context'));
        $this->assertFalse($options['ssl']['verify_peer']);
    }

    public function testUsesSslValidationByDefault()
    {
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 2\r\n\r\nhi");
        $request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->get('/');
        $this->factory = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\PhpStreamRequestFactory')
            ->setMethods(array('createStream'))
            ->getMock();
        $this->factory->expects($this->once())
            ->method('createStream')
            ->will($this->returnValue(new Stream(fopen('php://temp', 'r'))));
        $this->factory->fromRequest($request);
        $options = stream_context_get_options($this->readAttribute($this->factory, 'context'));
        $this->assertTrue($options['ssl']['verify_peer']);
        $this->assertSame($request->getCurlOptions()->get(CURLOPT_CAINFO), $options['ssl']['cafile']);
    }

    public function testBasicAuthAddsUserAndPassToUrl()
    {
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 2\r\n\r\nhi");
        $request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->get('/');
        $request->setAuth('Foo', 'Bar');
        $this->factory = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\PhpStreamRequestFactory')
            ->setMethods(array('createStream'))
            ->getMock();
        $this->factory->expects($this->once())
            ->method('createStream')
            ->will($this->returnValue(new Stream(fopen('php://temp', 'r'))));
        $this->factory->fromRequest($request);
        $this->assertContains('Foo:Bar@', (string) $this->readAttribute($this->factory, 'url'));
    }
}
