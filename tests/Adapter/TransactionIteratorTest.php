<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\TransactionIterator;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;

class TransactionIteratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidatesConstructor()
    {
        new TransactionIterator('foo', new Client(), []);
    }

    public function testCreatesTransactions()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $requests = [
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://test.com'),
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('POST', 'http://test.com'),
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('PUT', 'http://test.com'),
        ];
        $t = new TransactionIterator($requests, $/* Replaced /* Replaced /* Replaced client */ */ */, []);
        $this->assertEquals(0, $t->key());
        $this->assertTrue($t->valid());
        $this->assertEquals('GET', $t->current()->getRequest()->getMethod());
        $t->next();
        $this->assertEquals(1, $t->key());
        $this->assertTrue($t->valid());
        $this->assertEquals('POST', $t->current()->getRequest()->getMethod());
        $t->next();
        $this->assertEquals(2, $t->key());
        $this->assertTrue($t->valid());
        $this->assertEquals('PUT', $t->current()->getRequest()->getMethod());
    }

    public function testCanForeach()
    {
        $c = new Client();
        $requests = [
            $c->createRequest('GET', 'http://test.com'),
            $c->createRequest('POST', 'http://test.com'),
            $c->createRequest('PUT', 'http://test.com'),
        ];

        $t = new TransactionIterator(new \ArrayIterator($requests), $c, []);
        $methods = [];

        foreach ($t as $trans) {
            $this->assertInstanceOf(
                '/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\TransactionInterface',
                $trans
            );
            $methods[] = $trans->getRequest()->getMethod();
        }

        $this->assertEquals(['GET', 'POST', 'PUT'], $methods);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testValidatesEachElement()
    {
        $c = new Client();
        $requests = ['foo'];
        $t = new TransactionIterator(new \ArrayIterator($requests), $c, []);
        iterator_to_array($t);
    }
}
