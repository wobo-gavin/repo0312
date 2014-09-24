<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\CancelledResponse;

class CancelledResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\StateException
     */
    public function testThrowsWhenAccessed()
    {
        $r = new CancelledResponse();
        $this->assertTrue($r->cancelled());
        $r->getStatusCode();
    }
}
