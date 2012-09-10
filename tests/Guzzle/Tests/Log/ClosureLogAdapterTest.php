<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Log;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Log\ClosureLogAdapter;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Log\ClosureLogAdapter
 */
class ClosureLogAdapterTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testClosure()
    {
        $that = $this;
        $modified = null;
        $this->adapter = new ClosureLogAdapter(function($message, $priority, $extras = null) use ($that, &$modified) {
            $modified = array($message, $priority, $extras);
        });
        $this->adapter->log('test', LOG_NOTICE, 'localhost');
        $this->assertEquals(array('test', LOG_NOTICE, 'localhost'), $modified);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testThrowsExceptionWhenNotCallable()
    {
        $this->adapter = new ClosureLogAdapter(123);
    }
}
