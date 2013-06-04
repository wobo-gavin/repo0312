<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Log;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Log\PsrLogAdapter;
use Monolog\Logger;
use Monolog\Handler\TestHandler;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Log\PsrLogAdapter
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Log\AbstractLogAdapter
 */
class PsrLogAdapterTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testLogsMessagesToAdaptedObject()
    {
        $log = new Logger('test');
        $handler = new TestHandler();
        $log->pushHandler($handler);
        $adapter = new PsrLogAdapter($log);
        $adapter->log('test!', LOG_INFO);
        $this->assertTrue($handler->hasInfoRecords());
        $this->assertSame($log, $adapter->getLogObject());
    }
}
