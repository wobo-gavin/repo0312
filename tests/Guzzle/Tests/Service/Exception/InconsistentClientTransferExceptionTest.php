<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Exception;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\InconsistentClientTransferException;

class InconsistentClientTransferExceptionTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testStoresCommands()
    {
        $items = array('foo', 'bar');
        $e = new InconsistentClientTransferException($items);
        $this->assertEquals($items, $e->getCommands());
    }
}
