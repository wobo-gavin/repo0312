<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Log\Adapter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\LogAdapterInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\SimpleDbLogAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\SimpleDb\SimpleDbClient;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Server\Server;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Subject\Observer;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Subject\SubjectMediator;

/**
 * Test class for SimpleDbLogAdapter
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class SimpleDbLogAdapterTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase implements Observer
{
    /**
     * @var SimpleDbLogAdapter
     */
    protected $adapter;

    /**
     * @var SimpleDbClient
     */
    protected $/* Replaced /* Replaced /* Replaced client */ */ */;

    /**
     * @var int The number of requests issued by the Request object
     */
    protected $requestCount = 0;

    protected function setUp()
    {
        $this->requestCount = 0;
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.simple_db');
        $this->setMockResponse($this->/* Replaced /* Replaced /* Replaced client */ */ */, 'BatchPutAttributesResponse');
        $that = $this;

        $this->/* Replaced /* Replaced /* Replaced client */ */ */->getCreateRequestChain()->addFilter(new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\ClosureFilter(function($command) use($that) {
            $command->getSubjectMediator()->attach($that);
        }));
        
        // Wrap the logger and create a new SimpleDbLogAdapter
        $this->adapter = new SimpleDbLogAdapter($this->/* Replaced /* Replaced /* Replaced client */ */ */, array(
            'domain' => 'test'
        ));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\SimpleDbLogAdapter::init
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\LogAdapterException
     */
    public function testDomainMustBePassedInConstructor()
    {
        // Throws an exception
        $this->adapter = new SimpleDbLogAdapter($this->/* Replaced /* Replaced /* Replaced client */ */ */);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\SimpleDbLogAdapter::logMessage
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\SimpleDbLogAdapter::flush
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\AbstractQueuedLogAdapter::setMaxQueueSize
     */
    public function testLog()
    {
        // Set the max queue size to 2 so that 2 or more log messages will
        // trigger a flush
        $this->adapter->setMaxQueueSize(2);

        $this->adapter->log('Test', \LOG_NOTICE, '/* Replaced /* Replaced /* Replaced guzzle */ */ */', 'localhost');
        $this->adapter->log('Test 2', \LOG_NOTICE);

        // Two log messages were written, so make sure that a request was sent
        $this->assertEquals(1, $this->requestCount);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\AbstractQueuedLogAdapter::__destruct
     */
    public function testDestructorMustFlushLogs()
    {
        $this->adapter->setMaxQueueSize(999);
        $this->adapter->log('test');
        unset($this->adapter);
        
        // The __destruct() method was called and should flush the above log
        $this->assertEquals(1, $this->requestCount);
    }

    public function update(SubjectMediator $subject)
    {
        if ($subject->getState() == 'request.success') {
            $this->requestCount++;
        }
    }
}