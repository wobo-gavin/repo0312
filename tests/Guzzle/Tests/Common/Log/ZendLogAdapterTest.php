<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Log;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\LogAdapterInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\ZendLogAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\AbstractLogAdapter
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\ZendLogAdapter
 */
class ZendLogAdapterTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @var ZendLogAdapter
     */
    protected $adapter;

    /**
     * @var Logger
     */
    protected $log;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {        
        $this->log = new Logger(new Stream('php://output'));
        $this->adapter = new ZendLogAdapter($this->log);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\AbstractLogAdapter::__construct
     * @expectedException InvalidArgumentException
     */
    public function testEnforcesType()
    {
        // A successful construction
        $this->adapter = new ZendLogAdapter($this->log, new Collection());
        
        // Throws an exception
        $this->adapter = new ZendLogAdapter(new \stdClass());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\ZendLogAdapter::log
     * @outputBuffering enabled
     */
    public function testLogsMessagesToAdaptedObject()
    {
        // Test without a priority
        $this->adapter->log('test', \LOG_NOTICE, '/* Replaced /* Replaced /* Replaced guzzle */ */ */.common.log.adapter.zend_log_adapter', 'localhost');
        $this->assertEquals(1, substr_count(ob_get_contents(), 'test'));

        // Test with a priority
        $this->adapter->log('test', \LOG_ALERT);
        $this->assertEquals(2, substr_count(ob_get_contents(), 'test'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\AbstractLogAdapter::getLogObject
     */
    public function testExposesAdaptedLogObject()
    {
        $this->assertEquals($this->log, $this->adapter->getLogObject());
    }
}