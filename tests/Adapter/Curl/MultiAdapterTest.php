<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Adapter\Curl;

require_once __DIR__ . '/AbstractCurl.php';

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Curl\MultiAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\CompleteEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ErrorEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\HeadersEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Request;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Curl\MultiAdapter
 */
class MultiAdapterTest extends AbstractCurl
{
    protected function getAdapter($factory = null, $options = [])
    {
        return new MultiAdapter($factory ?: new MessageFactory(), $options);
    }

    public function testSendsSingleRequest()
    {
        self::$server->flush();
        self::$server->enqueue("HTTP/1.1 200 OK\r\nFoo: bar\r\nContent-Length: 0\r\n\r\n");
        $t = new Transaction(new Client(), new Request('GET', self::$server->getUrl()));
        $a = new MultiAdapter(new MessageFactory());
        $response = $a->send($t);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('bar', $response->getHeader('Foo'));
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\AdapterException
     * @expectedExceptionMessage cURL error -2:
     */
    public function testChecksCurlMultiResult()
    {
        MultiAdapter::throwMultiError(-2);
    }

    public function testChecksForCurlException()
    {
        $request = new Request('GET', 'http://httbin.org');
        $transaction = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Transaction')
            ->setMethods(['getRequest'])
            ->disableOriginalConstructor()
            ->getMock();
        $transaction->expects($this->exactly(2))
            ->method('getRequest')
            ->will($this->returnValue($request));
        $context = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Curl\BatchContext')
            ->setMethods(['throwsExceptions'])
            ->disableOriginalConstructor()
            ->getMock();
        $context->expects($this->once())
            ->method('throwsExceptions')
            ->will($this->returnValue(true));
        $a = new MultiAdapter(new MessageFactory());
        $r = new \ReflectionMethod($a, 'isCurlException');
        $r->setAccessible(true);
        try {
            $r->invoke($a, $transaction, ['result' => -10], $context, []);
            $this->fail('Did not throw');
        } catch (RequestException $e) {
            $this->assertSame($request, $e->getRequest());
            $this->assertContains('[curl] (#-10) ', $e->getMessage());
            $this->assertContains($request->getUrl(), $e->getMessage());
        }
    }

    public function testSendsParallelRequestsFromQueue()
    {
        $c = new Client();
        self::$server->flush();
        self::$server->enqueue([
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n"
        ]);
        $transactions = [
            new Transaction($c, new Request('GET', self::$server->getUrl())),
            new Transaction($c, new Request('PUT', self::$server->getUrl())),
            new Transaction($c, new Request('HEAD', self::$server->getUrl())),
            new Transaction($c, new Request('GET', self::$server->getUrl()))
        ];
        $a = new MultiAdapter(new MessageFactory());
        $a->sendAll(new \ArrayIterator($transactions), 2);
        foreach ($transactions as $t) {
            $response = $t->getResponse();
            $this->assertNotNull($response);
            $this->assertEquals(200, $response->getStatusCode());
        }
    }

    public function testCreatesAndReleasesHandlesWhenNeeded()
    {
        $a = new MultiAdapter(new MessageFactory());
        $c = new Client([
            'adapter'  => $a,
            'base_url' => self::$server->getUrl()
        ]);

        self::$server->flush();
        self::$server->enqueue([
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
            "HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n",
        ]);

        $ef = function (ErrorEvent $e) { throw $e->getException(); };

        $request1 = $c->createRequest('GET', '/');
        $request1->getEmitter()->on('headers', function () use ($a, $c, $ef) {
            $a->send(new Transaction($c, $c->createRequest('GET', '/', [
                'events' => [
                    'headers' => function () use ($a, $c, $ef) {
                        $r = $c->createRequest('GET', '/', [
                            'events' => ['error' => ['fn' => $ef, 'priority' => 9999]]
                        ]);
                        $r->getEmitter()->once('headers', function () use ($a, $c, $r) {
                            $a->send(new Transaction($c, $r));
                        });
                        $a->send(new Transaction($c, $r));
                        // Now, reuse an existing handle
                        $a->send(new Transaction($c, $r));
                        },
                    'error' => ['fn' => $ef, 'priority' => 9999]
                ]
            ])));
        });

        $request1->getEmitter()->on('error', $ef);

        $transactions = [
            new Transaction($c, $request1),
            new Transaction($c, $c->createRequest('PUT')),
            new Transaction($c, $c->createRequest('HEAD'))
        ];

        $a->sendAll(new \ArrayIterator($transactions), 2);

        foreach ($transactions as $index => $t) {
            $response = $t->getResponse();
            $this->assertInstanceOf(
                '/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\\Message\\ResponseInterface',
                $response,
                'Transaction at index ' . $index . ' did not populate response'
            );
            $this->assertEquals(200, $response->getStatusCode());
        }
    }

    public function testThrowsAndReleasesWhenErrorDuringCompleteEvent()
    {
        self::$server->flush();
        self::$server->enqueue("HTTP/1.1 500 Internal Server Error\r\nContent-Length: 0\r\n\r\n");
        $request = new Request('GET', self::$server->getUrl());
        $request->getEmitter()->on('complete', function (CompleteEvent $e) {
            throw new RequestException('foo', $e->getRequest());
        });
        $t = new Transaction(new Client(), $request);
        $a = new MultiAdapter(new MessageFactory());
        try {
            $a->send($t);
            $this->fail('Did not throw');
        } catch (RequestException $e) {
            $this->assertSame($request, $e->getRequest());
        }
    }
}
