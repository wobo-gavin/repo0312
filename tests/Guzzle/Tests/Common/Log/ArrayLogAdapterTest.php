<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Log;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\LogAdapterInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\ArrayLogAdapter;

class ArrayLogAdapterTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testLog()
    {
        $adapter = new ArrayLogAdapter();

        $adapter->log('test', \LOG_NOTICE, 'localhost');
        $this->assertEquals(array(array('message' => 'test', 'priority' => \LOG_NOTICE, 'extras' => 'localhost')), $adapter->getLogs());
    }

    public function testClearLog()
    {
        $adapter = new ArrayLogAdapter();

        $adapter->log('test', \LOG_NOTICE, 'localhost');
        $adapter->clearLogs();
        $this->assertEquals(array(), $adapter->getLogs());
    }
}
