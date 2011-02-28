<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Log;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Logger;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\LogAdapterInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\ZendLogAdapter;

/**
 * Test class for Logger.
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class LoggerTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var ZendLogAdapter
     */
    protected $adapterA;
    
    /**
     * @var ZendLogAdapter
     */
    protected $adapterB;

    /**
     * @var \Zend_Log
     */
    protected $zendLogA;

    /**
     * @var \Zend_Log
     */
    protected $zendLogB;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        // Make sure that the Zend Framework is in the PHP include path
        if (!class_exists('Zend_Log')) {
            $this->markTestSkipped(
                'The Zend Framework is not present in your path'
            );
            return;
        }
        
        $this->logger = new Logger();

        // Create Zend Framework log objects with a log writer to standard out
        $this->zendLogA = new \Zend_Log(new \Zend_Log_Writer_Stream('php://output'));
        $this->zendLogB = new \Zend_Log(new \Zend_Log_Writer_Stream('php://output'));

        // Create a new log adapter and log to the php standard out
        // Not using any minimum priority as we aren't testing adapters in this
        // test case
        $this->adapterA = new ZendLogAdapter($this->zendLogA);
        $this->adapterB = new ZendLogAdapter($this->zendLogB);
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Logger::__construct
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Logger
     */
    public function testConstruct()
    {
        $zlog = new \Zend_Log(new \Zend_Log_Writer_Stream('php://output'));
        $adapter = new ZendLogAdapter($zlog);
        $this->logger = new Logger(array($adapter));
        $this->assertEquals(array($adapter), $this->logger->getAdapters());
        
        // Throw an exception when using invalid adapters in the constructor
        try {
            $logger = new Logger(array(
                new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\NullObject()
            ));
        } catch (\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\LogException $e) {
        }

        // Test the logger adapter matching
        $logger = new Logger(array(
            $zlog
        ));

        $adapters = $logger->getAdapters();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\ZendLogAdapter', $adapters[0]);
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Logger::addAdapter
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Logger::getAdapters
     */
    public function testAddAdapter()
    {        
        $this->assertEquals($this->adapterA, $this->logger->addAdapter($this->adapterA));
        $this->assertEquals(array($this->adapterA), $this->logger->getAdapters());
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Logger::getAdapters
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Logger::addAdapter
     * @depends testAddAdapter
     */
    public function testGetAdapters()
    {
        $adapters = $this->logger->getAdapters();
        $this->assertTrue(empty($adapters));
        $this->assertEquals($this->adapterA, $this->logger->addAdapter($this->adapterA));
        $this->assertEquals(array($this->adapterA), $this->logger->getAdapters());
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Logger::removeAdapter
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Logger::addAdapter
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Logger::getAdapters
     */
    public function testRemoveAdapter()
    {
        $this->logger->addAdapter($this->adapterA);
        $this->logger->addAdapter($this->adapterB);
        $this->assertEquals($this->adapterA, $this->logger->removeAdapter($this->adapterA));
        $this->assertEquals(array($this->adapterB), $this->logger->getAdapters());
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Logger::log
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Logger::addAdapter
     * @depends testAddAdapter
     * @outputBuffering enabled
     */
    public function testLog()
    {
        // Write without any attached adapters
        $this->assertEquals($this->logger, $this->logger->log('message', \LOG_INFO));

        // Add a couple adapters
        $this->logger->addAdapter($this->adapterA);
        $this->logger->addAdapter($this->adapterB);

        // Log a message at the highest priority
        $this->logger->log('test', \LOG_EMERG);

        // Both adapters should have picked up the message
        $this->assertEquals(2, substr_count(ob_get_contents(), 'test'));        
    }
}