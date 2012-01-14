<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Log;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\LogAdapterInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\ClosureLogAdapter;

class ClosureLogAdapterTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @var string Variable that the closure will modifiy
     */
    public $modified;

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\ClosureLogAdapter
     */
    public function testClosure()
    {
        $that = $this;

        $this->adapter = new ClosureLogAdapter(function($message, $priority, $extras = null) use ($that) {
            $that->modified = array($message, $priority, $extras);
        });

        $this->adapter->log('test', \LOG_NOTICE, 'localhost');
        $this->assertEquals(array('test', \LOG_NOTICE, 'localhost'), $this->modified);
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\ClosureLogAdapter
     * @expectedException InvalidArgumentException
     */
    public function testThrowsExceptionWhenNotCallable()
    {
        $abc = 123;
        $this->adapter = new ClosureLogAdapter($abc);
    }
}