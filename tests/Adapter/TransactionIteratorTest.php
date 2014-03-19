<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\TransactionIterator;

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
        $trans = new TransactionIterator($requests, $/* Replaced /* Replaced /* Replaced client */ */ */, []);
        $this->assertEquals(0, $trans->key());
        $this->assertTrue($trans->valid());
        $this->assertEquals('GET', $trans->current()->getRequest()->getMethod());
        $trans->next();
        $this->assertEquals(1, $trans->key());
        $this->assertTrue($trans->valid());
        $this->assertEquals('POST', $trans->current()->getRequest()->getMethod());
        $trans->next();
        $this->assertEquals(2, $trans->key());
        $this->assertTrue($trans->valid());
        $this->assertEquals('PUT', $trans->current()->getRequest()->getMethod());
    }

    public function testCanForeach()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $requests = [
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://test.com'),
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('POST', 'http://test.com'),
            $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('PUT', 'http://test.com'),
        ];

        $trans = new TransactionIterator(new \ArrayIterator($requests), $/* Replaced /* Replaced /* Replaced client */ */ */, []);
        $methods = [];

        foreach ($trans as $t) {
            $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\TransactionInterface', $t);
            $methods[] = $t->getRequest()->getMethod();
        }

        $this->assertEquals(['GET', 'POST', 'PUT'], $methods);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testValidatesEachElement()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $requests = ['foo'];
        $trans = new TransactionIterator(new \ArrayIterator($requests), $/* Replaced /* Replaced /* Replaced client */ */ */, []);
        iterator_to_array($trans);
    }

    public function testRegistersEvents()
    {
        $fn = function() {};
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $requests = [$/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://test.com')];
        $trans = new TransactionIterator(new \ArrayIterator($requests), $/* Replaced /* Replaced /* Replaced client */ */ */, [
            'before'   => $fn,
            'complete' => $fn,
            'error'    => $fn,
        ]);

        $t = iterator_to_array($trans)[0];
        $this->assertSame($fn, $t->getRequest()->getEmitter()->listeners('before')[0]);
        $this->assertSame($fn, $t->getRequest()->getEmitter()->listeners('complete')[2]);
        $this->assertSame($fn, $t->getRequest()->getEmitter()->listeners('error')[0]);
    }

    public function testRegistersEventsWithPriorities()
    {
        $fn = function() {};
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $requests = [$/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://test.com')];
        $trans = new TransactionIterator(new \ArrayIterator($requests), $/* Replaced /* Replaced /* Replaced client */ */ */, [
            'before'   => [$fn, 99],
            'complete' => [$fn, 99],
            'error'    => [$fn, 99]
        ]);

        $t = iterator_to_array($trans)[0];
        $this->assertSame($fn, $t->getRequest()->getEmitter()->listeners('before')[0]);
        $this->assertSame($fn, $t->getRequest()->getEmitter()->listeners('complete')[2]);
        $this->assertSame($fn, $t->getRequest()->getEmitter()->listeners('error')[0]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidatesEvents()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $requests = [$/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://test.com')];
        new TransactionIterator(new \ArrayIterator($requests), $/* Replaced /* Replaced /* Replaced client */ */ */, [
            'before' => 'foo'
        ]);
    }
}
