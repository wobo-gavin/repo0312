<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ProgressEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Transaction;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ProgressEvent
 */
class ProgressEventTest extends \PHPUnit_Framework_TestCase
{
    public function testContainsNumbers()
    {
        $t = new Transaction(new Client(), new Request('GET', 'http://a.com'));
        $p = new ProgressEvent($t, 2, 1, 3, 0);
        $this->assertSame($t->request, $p->getRequest());
        $this->assertSame($t->/* Replaced /* Replaced /* Replaced client */ */ */, $p->getClient());
        $this->assertEquals(2, $p->downloadSize);
        $this->assertEquals(1, $p->downloaded);
        $this->assertEquals(3, $p->uploadSize);
        $this->assertEquals(0, $p->uploaded);
    }
}
