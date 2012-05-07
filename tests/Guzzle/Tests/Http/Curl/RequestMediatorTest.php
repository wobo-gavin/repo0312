<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Curl;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\EntityEnclosingRequest;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\RequestMediator;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\RequestMediator
 */
class RequestMediatorTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public $events = array();

    public function event($event)
    {
        $this->events[] = $event;
    }

    public function testEmitsEvents()
    {
        $request = new EntityEnclosingRequest('PUT', 'http://www.example.com');
        $request->setBody('foo');
        $request->setResponse(new Response(200));

        // Ensure that IO events are emitted
        $request->getParams()->set('curl.emit_io', true);

        // Attach listeners for each event type
        $request->getEventDispatcher()->addListener('curl.callback.progress', array($this, 'event'));
        $request->getEventDispatcher()->addListener('curl.callback.read', array($this, 'event'));
        $request->getEventDispatcher()->addListener('curl.callback.write', array($this, 'event'));

        $mediator = new RequestMediator($request, true);

        $mediator->progress('a', 'b', 'c', 'd');
        $this->assertEquals(1, count($this->events));
        $this->assertEquals('curl.callback.progress', $this->events[0]->getName());

        $this->assertEquals(3, $mediator->writeResponseBody('foo', 'bar'));
        $this->assertEquals(2, count($this->events));
        $this->assertEquals('curl.callback.write', $this->events[1]->getName());
        $this->assertEquals('bar', $this->events[1]['write']);
        $this->assertSame($request, $this->events[1]['request']);

        $this->assertEquals('foo', $mediator->readRequestBody('a', 'b', 3));
        $this->assertEquals(3, count($this->events));
        $this->assertEquals('curl.callback.read', $this->events[2]->getName());
        $this->assertEquals('foo', $this->events[2]['read']);
        $this->assertSame($request, $this->events[2]['request']);
    }
}
