<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Adapter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Response;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Transaction
 */
class TransactionTest extends \PHPUnit_Framework_TestCase
{
    public function testHasRequestAndClient()
    {
        $c = new Client();
        $req = new Request('GET', '/');
        $response = new Response(200);
        $t = new Transaction($c, $req);
        $this->assertSame($c, $t->getClient());
        $this->assertSame($req, $t->getRequest());
        $this->assertNull($t->getResponse());
        $t->setResponse($response);
        $this->assertSame($response, $t->getResponse());
    }
}
