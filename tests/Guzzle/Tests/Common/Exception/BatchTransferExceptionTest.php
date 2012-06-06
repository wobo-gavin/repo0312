<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Exception;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\BatchTransferException;

class BatchTransferExceptionTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testContainsBatch()
    {
        $a = new \Exception('Baz!');
        $b = new BatchTransferException(array('foo'), $a);
        $this->assertEquals(array('foo'), $b->getBatch());
        $this->assertSame($a, $b->getPrevious());
    }
}
