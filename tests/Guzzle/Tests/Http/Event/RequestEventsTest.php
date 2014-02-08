<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestBeforeSendEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestErrorEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestEvents;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestEvents
 */
class RequestEventsTest extends \PHPUnit_Framework_TestCase
{
    public function testEmitsAfterSendEvent()
    {
        $res = null;
        $t = new Transaction(new Client(), new Request('GET', '/'));
        $t->setResponse(new Response(200));
        $t->getRequest()->getEmitter()->on(RequestEvents::AFTER_SEND, function ($e) use (&$res) {
            $res = $e;
        });
        RequestEvents::emitAfterSendEvent($t);
        $this->assertSame($res->getClient(), $t->getClient());
        $this->assertSame($res->getRequest(), $t->getRequest());
        $this->assertEquals('/', $t->getResponse()->getEffectiveUrl());
    }

    public function testEmitsAfterSendEventAndEmitsErrorIfNeeded()
    {
        $ex2 = $res = null;
        $request = new Request('GET', '/');
        $t = new Transaction(new Client(), $request);
        $t->setResponse(new Response(200));
        $ex = new RequestException('foo', $request);
        $t->getRequest()->getEmitter()->on(RequestEvents::AFTER_SEND, function ($e) use ($ex) {
            $ex->e = $e;
            throw $ex;
        });
        $t->getRequest()->getEmitter()->on(RequestEvents::ERROR, function ($e) use (&$ex2) {
            $ex2 = $e->getException();
            $e->stopPropagation();
        });
        RequestEvents::emitAfterSendEvent($t);
        $this->assertSame($ex, $ex2);
    }

    public function testBeforeSendEmitsErrorEvent()
    {
        $ex = new \Exception('Foo');
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $request = new Request('GET', '/');
        $response = new Response(200);
        $t = new Transaction($/* Replaced /* Replaced /* Replaced client */ */ */, $request);
        $beforeCalled = $errCalled = 0;

        $request->getEmitter()->on(
            RequestEvents::BEFORE_SEND,
            function (RequestBeforeSendEvent $e) use ($request, $/* Replaced /* Replaced /* Replaced client */ */ */, &$beforeCalled, $ex) {
                $this->assertSame($request, $e->getRequest());
                $this->assertSame($/* Replaced /* Replaced /* Replaced client */ */ */, $e->getClient());
                $beforeCalled++;
                throw $ex;
            }
        );

        $request->getEmitter()->on(
            RequestEvents::ERROR,
            function (RequestErrorEvent $e) use (&$errCalled, $response, $ex) {
                $errCalled++;
                $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\RequestException', $e->getException());
                $this->assertSame($ex, $e->getException()->getPrevious());
                $e->intercept($response);
            }
        );

        RequestEvents::emitBeforeSendEvent($t);
        $this->assertEquals(1, $beforeCalled);
        $this->assertEquals(1, $errCalled);
        $this->assertSame($response, $t->getResponse());
    }

    public function testThrowsUnInterceptedErrors()
    {
        $ex = new \Exception('Foo');
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $request = new Request('GET', '/');
        $t = new Transaction($/* Replaced /* Replaced /* Replaced client */ */ */, $request);
        $errCalled = 0;

        $request->getEmitter()->on(RequestEvents::BEFORE_SEND, function (RequestBeforeSendEvent $e) use ($ex) {
            throw $ex;
        });

        $request->getEmitter()->on(RequestEvents::ERROR, function (RequestErrorEvent $e) use (&$errCalled) {
            $errCalled++;
        });

        try {
            RequestEvents::emitBeforeSendEvent($t);
            $this->fail('Did not throw');
        } catch (RequestException $e) {
            $this->assertEquals(1, $errCalled);
        }
    }
}
