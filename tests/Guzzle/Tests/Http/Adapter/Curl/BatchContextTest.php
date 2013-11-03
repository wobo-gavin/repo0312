<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Adapter\Curl;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Curl\BatchContext;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Curl\BatchContext
 */
class BatchContextTest extends \PHPUnit_Framework_TestCase
{
    public function testValidatesTransactionsAreNotAddedTwice()
    {
        $m = curl_multi_init();
        $b = new BatchContext($m);
        $h = curl_init();
        $t = new Transaction(new Client(), new Request('GET', '/'));
        $b->addTransaction($t, $h);
        try {
            $b->addTransaction($t, $h);
            $this->fail('Did not throw');
        } catch (\RuntimeException $e) {
            curl_close($h);
            curl_multi_close($m);
        }
    }

    public function testManagesHandles()
    {
        $m = curl_multi_init();
        $b = new BatchContext($m);
        $h = curl_init();
        $t = new Transaction(new Client(), new Request('GET', '/'));
        $b->addTransaction($t, $h);
        $this->assertEquals([$t], $b->getTransactions());
        $b->removeTransaction($t);
        $this->assertEquals([], $b->getTransactions());
        curl_multi_close($m);
    }
}
