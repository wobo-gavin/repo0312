<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Adapter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\StreamingProxyAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Response;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\StreamingProxyAdapter
 */
class StreamingProxyAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testSendsWithDefaultAdapter()
    {
        $response = new Response(200);
        $mock = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\AdapterInterface')
            ->setMethods(['send'])
            ->getMockForAbstractClass();
        $mock->expects($this->once())
            ->method('send')
            ->will($this->returnValue($response));
        $streaming = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\AdapterInterface')
            ->setMethods(['send'])
            ->getMockForAbstractClass();
        $streaming->expects($this->never())
            ->method('send');

        $s = new StreamingProxyAdapter($mock, $streaming);
        $this->assertSame($response, $s->send(new Transaction(new Client(), new Request('GET', '/'))));
    }

    public function testSendsWithStreamingAdapter()
    {
        $response = new Response(200);
        $mock = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\AdapterInterface')
            ->setMethods(['send'])
            ->getMockForAbstractClass();
        $mock->expects($this->never())
            ->method('send');
        $streaming = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\AdapterInterface')
            ->setMethods(['send'])
            ->getMockForAbstractClass();
        $streaming->expects($this->once())
            ->method('send')
            ->will($this->returnValue($response));
        $request = new Request('GET', '/');
        $request->getConfig()->set('stream', true);
        $s = new StreamingProxyAdapter($mock, $streaming);
        $this->assertSame($response, $s->send(new Transaction(new Client(), $request)));
    }
}