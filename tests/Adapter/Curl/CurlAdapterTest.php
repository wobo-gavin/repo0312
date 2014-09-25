<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Adapter\Curl;

require_once __DIR__ . '/AbstractCurl.php';

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Curl\CurlAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ErrorEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\HeadersEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\ServerException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\BeforeEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Server;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Curl\CurlAdapter
 */
class CurlAdapterTest extends AbstractCurl
{
    protected function setUp()
    {
        if (!function_exists('curl_reset')) {
            $this->markTestSkipped('curl_reset() is not available');
        }
    }

    protected function getAdapter($factory = null, $options = [])
    {
        return new CurlAdapter($factory ?: new MessageFactory(), $options);
    }

    public function testCanSetMaxHandles()
    {
        $a = new CurlAdapter(new MessageFactory(), ['max_handles' => 10]);
        $this->assertEquals(10, $this->readAttribute($a, 'maxHandles'));
    }

    public function testCanInterceptBeforeSending()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $request = new Request('GET', 'http://httpbin.org/get');
        $response = new Response(200);
        $request->getEmitter()->on(
            'before',
            function (BeforeEvent $e) use ($response) {
                $e->intercept($response);
            }
        );
        $transaction = new Transaction($/* Replaced /* Replaced /* Replaced client */ */ */, $request);
        $f = 'does_not_work';
        $a = new CurlAdapter(new MessageFactory(), ['handle_factory' => $f]);
        $a->send($transaction);
        $this->assertSame($response, $transaction->getResponse());
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException
     * @expectedExceptionMessage cURL error
     */
    public function testThrowsCurlErrors()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://localhost:123', [
            'connect_timeout' => 0.001,
            'timeout' => 0.001,
        ]);
        $transaction = new Transaction($/* Replaced /* Replaced /* Replaced client */ */ */, $request);
        $a = new CurlAdapter(new MessageFactory());
        $a->send($transaction);
    }

    public function testHandlesCurlErrors()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://localhost:123', [
            'connect_timeout' => 0.001,
            'timeout' => 0.001,
        ]);
        $r = new Response(200);
        $request->getEmitter()->on('error', function (ErrorEvent $e) use ($r) {
            $e->intercept($r);
        });
        $transaction = new Transaction($/* Replaced /* Replaced /* Replaced client */ */ */, $request);
        $a = new CurlAdapter(new MessageFactory());
        $a->send($transaction);
        $this->assertSame($r, $transaction->getResponse());
    }

    public function testReleasesAdditionalEasyHandles()
    {
        Server::flush();
        Server::enqueue([
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n"
        ]);
        $a = new CurlAdapter(new MessageFactory(), ['max_handles' => 2]);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => Server::$url, 'adapter' => $a]);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/', [
            'events' => [
                'headers' => function (HeadersEvent $e) use ($/* Replaced /* Replaced /* Replaced client */ */ */) {
                    $/* Replaced /* Replaced /* Replaced client */ */ */->get('/', [
                        'events' => [
                            'headers' => function (HeadersEvent $e) {
                                $e->getClient()->get('/');
                            }
                        ]
                    ]);
                }
            ]
        ]);
        $transaction = new Transaction($/* Replaced /* Replaced /* Replaced client */ */ */, $request);
        $a->send($transaction);
        $this->assertCount(2, $this->readAttribute($a, 'handles'));
    }

    public function testDoesNotSaveToWhenFailed()
    {
        Server::flush();
        Server::enqueue([
            "HTTP/1.1 500 Internal Server Error\r\nContent-Length: 0\r\n\r\n"
        ]);

        $tmp = tempnam('/tmp', 'test_save_to');
        unlink($tmp);
        $a = new CurlAdapter(new MessageFactory());
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => Server::$url, 'adapter' => $a]);
        try {
            $/* Replaced /* Replaced /* Replaced client */ */ */->get('/', ['save_to' => $tmp]);
        } catch (ServerException $e) {
            $this->assertFileNotExists($tmp);
        }
    }

    public function testRewindsStreamOnComplete()
    {
        Server::flush();
        Server::enqueue("HTTP/1.1 200 OK\r\nFoo: bar\r\nContent-Length: 4\r\n\r\ntest");
        $t = new Transaction(new Client(), new Request('GET', Server::$url));
        $a = new CurlAdapter(new MessageFactory());
        $response = $a->send($t);
        $this->assertEquals('test', $response->getBody()->read(4));
    }
}
