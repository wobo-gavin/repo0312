<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Transaction;

class TransactionTest extends \PHPUnit_Framework_TestCase
{
    public function testHoldsData()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $request = new Request('GET', 'http://www.foo.com');
        $t = new Transaction($/* Replaced /* Replaced /* Replaced client */ */ */, $request);
        $this->assertSame($/* Replaced /* Replaced /* Replaced client */ */ */, $t->/* Replaced /* Replaced /* Replaced client */ */ */);
        $this->assertSame($request, $t->request);
        $response = new Response(200);
        $t->response = $response;
        $this->assertSame($response, $t->response);
    }
}
