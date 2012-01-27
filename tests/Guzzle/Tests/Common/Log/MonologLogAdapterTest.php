<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Log;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\MonologLogAdapter;
use Monolog\Logger;
use Monolog\Handler\TestHandler;

class MonologLogAdapterTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\MonologLogAdapter::__construct
     * @expectedException InvalidArgumentException
     */
    public function testEnforcesType()
    {
        // A successful construction
        $log = new Logger('test');
        $log->pushHandler(new TestHandler());
        $adapter = new MonologLogAdapter($log);

        // Throws an exception
        $this->adapter = new MonologLogAdapter(new \stdClass());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\MonologLogAdapter::log
     */
    public function testLogsMessagesToAdaptedObject()
    {
        $log = new Logger('test');
        $handler = new TestHandler();
        $log->pushHandler($handler);
        $adapter = new MonologLogAdapter($log);

        $adapter->log('test!', LOG_INFO);

        $this->assertTrue($handler->hasInfoRecords());
    }
}
