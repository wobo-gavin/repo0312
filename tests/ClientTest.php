<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Handler\MockHandler;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Response;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    public function testUsesDefaultHandler()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        Server::enqueue([new Response(200, ['Content-Length' => 0])]);
        $response = $/* Replaced /* Replaced /* Replaced client */ */ */->get(Server::$url);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Magic request methods require a URI and optional options array
     */
    public function testValidatesArgsForMagicMethods()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->get();
    }

    public function testCanSendMagicAsyncRequests()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        Server::flush();
        Server::enqueue([new Response(200, ['Content-Length' => 2], 'hi')]);
        $p = $/* Replaced /* Replaced /* Replaced client */ */ */->getAsync(Server::$url, ['query' => ['test' => 'foo']]);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Promise\PromiseInterface', $p);
        $this->assertEquals(200, $p->wait()->getStatusCode());
        $received = Server::received(true);
        $this->assertCount(1, $received);
        $this->assertEquals('test=foo', $received[0]->getUri()->getQuery());
    }

    public function testCanSendSynchronously()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['handler' => new MockHandler(new Response())]);
        $request = new Request('GET', 'http://example.com');
        $r = $/* Replaced /* Replaced /* Replaced client */ */ */->send($request);
        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $r);
        $this->assertEquals(200, $r->getStatusCode());
    }

    public function testClientHasOptions()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client([
            'base_uri' => 'http://foo.com',
            'timeout'  => 2,
            'headers'  => ['bar' => 'baz'],
            'handler'  => new MockHandler()
        ]);
        $base = $/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultOption('base_uri');
        $this->assertEquals('http://foo.com', (string) $base);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */\Uri', $base);
        $this->assertNull($/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultOption('handler'));
        $this->assertEquals(2, $/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultOption('timeout'));
        $this->assertArrayHasKey('timeout', $/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultOption());
        $this->assertArrayHasKey('headers', $/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultOption());
    }

    public function testCanMergeOnBaseUri()
    {
        $mock = new MockHandler(new Response());
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client([
            'base_uri' => 'http://foo.com/bar/',
            'handler'  => $mock
        ]);
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('baz');
        $this->assertEquals(
            'http://foo.com/bar/baz',
            $mock->getLastRequest()->getUri()
        );
    }

    public function testCanUseRelativeUriWithSend()
    {
        $mock = new MockHandler(new Response());
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client([
            'handler'  => $mock,
            'base_uri' => 'http://bar.com'
        ]);
        $this->assertEquals('http://bar.com', (string) $/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultOption('base_uri'));
        $request = new Request('GET', '/baz');
        $/* Replaced /* Replaced /* Replaced client */ */ */->send($request);
        $this->assertEquals(
            'http://bar.com/baz',
            (string) $mock->getLastRequest()->getUri()
        );
    }

    public function testMergesDefaultOptionsAndDoesNotOverwriteUa()
    {
        $c = new Client(['headers' => ['User-agent' => 'foo']]);
        $this->assertEquals(['User-agent' => 'foo'], $c->getDefaultOption('headers'));
        $this->assertInternalType('array', $c->getDefaultOption('allow_redirects'));
        $this->assertTrue($c->getDefaultOption('http_errors'));
        $this->assertTrue($c->getDefaultOption('decode_content'));
        $this->assertTrue($c->getDefaultOption('verify'));
    }

    public function testDoesNotOverwriteHeaderWithDefault()
    {
        $mock = new MockHandler(new Response());
        $c = new Client([
            'headers' => ['User-agent' => 'foo'],
            'handler' => $mock
        ]);
        $c->get('http://example.com', ['headers' => ['User-Agent' => 'bar']]);
        $this->assertEquals('bar', $mock->getLastRequest()->getHeader('User-Agent'));
    }

    public function testCanUnsetRequestOptionWithNull()
    {
        $mock = new MockHandler(new Response());
        $c = new Client([
            'headers' => ['foo' => 'bar'],
            'handler' => $mock
        ]);
        $c->get('http://example.com', ['headers' => null]);
        $this->assertFalse($mock->getLastRequest()->hasHeader('foo'));
    }
}
