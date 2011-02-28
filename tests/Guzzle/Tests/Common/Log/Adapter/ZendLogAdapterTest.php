<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Log\Adapter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\LogAdapterInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\ZendLogAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\Chain;

/**
 * Test class for ZendLogAdapter
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class ZendLogAdapterTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @var ZendLogAdapter
     */
    protected $adapter;

    /**
     * @var Zend_Log
     */
    protected $log;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {        
        $this->log = new \Zend_Log(new \Zend_Log_Writer_Stream('php://output'));
        $this->adapter = new ZendLogAdapter($this->log);
    }

    /**
     * Check for the existence of the Zend_Framework in your path
     */
    protected function zfSkip()
    {
        if (!class_exists('\Zend_Log')) {
            $this->markTestSkipped(
                'The Zend Framework is not present in your path'
            );
            return;
        }
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\AbstractLogAdapter::__construct
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\LogAdapterException
     */
    public function testConstruct()
    {
        $this->zfSkip();
        
        $chain = new Chain();

        // A successful construction
        $this->adapter = new ZendLogAdapter($this->log, new Collection(), $chain);
        $this->assertEquals($chain, $this->adapter->getFilterChain());

        // Throws an exception
        $this->adapter = new ZendLogAdapter(new \stdClass());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\AbstractLogAdapter::log
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\ZendLogAdapter::logMessage
     * @outputBuffering enabled
     */
    public function testLog()
    {
        $this->zfSkip();
        
        // Test without a priority
        $this->adapter->log('test', \LOG_NOTICE, '/* Replaced /* Replaced /* Replaced guzzle */ */ */.common.log.adapter.zend_log_adapter', 'localhost');
        $this->assertEquals(1, substr_count(ob_get_contents(), 'test'));

        // Test with a priority
        $this->adapter->log('test', \LOG_ALERT);
        $this->assertEquals(2, substr_count(ob_get_contents(), 'test'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\AbstractLogAdapter::getLogObject
     */
    public function testGetLogObject()
    {
        $this->zfSkip();
        
        $this->assertEquals($this->log, $this->adapter->getLogObject());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\AbstractLogAdapter::__call
     * @expectedException Zend_Log_Exception
     */
    public function testAdapterMustProxyToWrappedObject()
    {
        $this->zfSkip();
        
        $this->adapter->addPriority('EMERG', 0);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\AbstractLogAdapter::__call
     * @expectedException BadMethodCallException
     */
    public function testAdapterThrowExceptionsWhenProxyingToMissingMethods()
    {
        $this->zfSkip();
        
        $this->adapter->foo();
    }
}