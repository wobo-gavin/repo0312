<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Message;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestAfterSendEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Subscriber\HttpError;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Subscriber\Mock;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Subscriber\HttpError
 */
class HttpErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testIgnoreSuccessfulRequests()
    {
        $event = $this->getEvent();
        $event->intercept(new Response(200));
        (new HttpError())->onRequestAfterSend($event);
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\ClientErrorResponseException
     */
    public function testThrowsClientExceptionOnFailure()
    {
        $event = $this->getEvent();
        $event->intercept(new Response(403));
        (new HttpError())->onRequestAfterSend($event);
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\ServerErrorResponseException
     */
    public function testThrowsServerExceptionOnFailure()
    {
        $event = $this->getEvent();
        $event->intercept(new Response(500));
        (new HttpError())->onRequestAfterSend($event);
    }

    private function getEvent()
    {
        return new RequestAfterSendEvent(new Transaction(new Client(), new Request('PUT', '/')));
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\ClientErrorResponseException
     */
    public function testFullTransaction()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter()->addSubscriber(new Mock([
            new Response(403)
        ]));
        $/* Replaced /* Replaced /* Replaced client */ */ */->get('/');
    }
}
