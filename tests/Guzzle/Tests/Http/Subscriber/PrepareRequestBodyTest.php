<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Message;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestBeforeSendEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Subscriber\PrepareRequestBody;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\NoSeekStream;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\Stream;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Subscriber\PrepareRequestBody
 */
class PrepareRequestBodyTest extends \PHPUnit_Framework_TestCase
{
    public function testIgnoresRequestsWithNoBody()
    {
        $s = new PrepareRequestBody();
        $t = $this->getTrans();
        $s->onRequestBeforeSend(new RequestBeforeSendEvent($t));
        $this->assertFalse($t->getRequest()->hasHeader('Expect'));
    }

    public function testAppliesPostBody()
    {
        $s = new PrepareRequestBody();
        $t = $this->getTrans();
        $p = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Post\PostBody')
            ->setMethods(['applyRequestHeaders'])
            ->getMockForAbstractClass();
        $p->expects($this->once())
            ->method('applyRequestHeaders');
        $t->getRequest()->setBody($p);
        $s->onRequestBeforeSend(new RequestBeforeSendEvent($t));
    }

    public function testAddsExpectHeaderWithTrue()
    {
        $s = new PrepareRequestBody();
        $t = $this->getTrans();
        $t->getRequest()->getConfig()->set('expect', true);
        $t->getRequest()->setBody(Stream::factory('foo'));
        $s->onRequestBeforeSend(new RequestBeforeSendEvent($t));
        $this->assertEquals('100-Continue', $t->getRequest()->getHeader('Expect'));
    }

    public function testAddsExpectHeaderBySize()
    {
        $s = new PrepareRequestBody();
        $t = $this->getTrans();
        $t->getRequest()->getConfig()->set('expect', 2);
        $t->getRequest()->setBody(Stream::factory('foo'));
        $s->onRequestBeforeSend(new RequestBeforeSendEvent($t));
        $this->assertTrue($t->getRequest()->hasHeader('Expect'));
    }

    public function testDoesNotAddExpectHeaderBySize()
    {
        $s = new PrepareRequestBody();
        $t = $this->getTrans();
        $t->getRequest()->getConfig()->set('expect', 10);
        $t->getRequest()->setBody(Stream::factory('foo'));
        $s->onRequestBeforeSend(new RequestBeforeSendEvent($t));
        $this->assertFalse($t->getRequest()->hasHeader('Expect'));
    }

    public function testAddsExpectHeaderForNonSeekable()
    {
        $s = new PrepareRequestBody();
        $t = $this->getTrans();
        $t->getRequest()->setBody(new NoSeekStream(Stream::factory('foo')));
        $s->onRequestBeforeSend(new RequestBeforeSendEvent($t));
        $this->assertTrue($t->getRequest()->hasHeader('Expect'));
    }

    public function testRemovesContentLengthWhenSendingWithChunked()
    {
        $s = new PrepareRequestBody();
        $t = $this->getTrans();
        $t->getRequest()->setBody(Stream::factory('foo'));
        $t->getRequest()->setHeader('Transfer-Encoding', 'chunked');
        $s->onRequestBeforeSend(new RequestBeforeSendEvent($t));
        $this->assertFalse($t->getRequest()->hasHeader('Content-Length'));
    }

    private function getTrans()
    {
        return new Transaction(new Client(), new Request('PUT', '/'));
    }
}
