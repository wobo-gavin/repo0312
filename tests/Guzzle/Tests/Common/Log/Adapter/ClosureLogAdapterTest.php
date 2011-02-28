<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Log\Adapter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\LogAdapterInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\ClosureLogAdapter;

/**
 * Test class for ClosureLogAdapter
 * 
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class ClosureLogAdapterTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @var string Variable that the closure will modifiy
     */
    public $modified;

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\ClosureLogAdapter
     */
    public function testClosure()
    {
        $that = $this;

        $this->adapter = new ClosureLogAdapter(function($message, $priority, $category, $host) use ($that) {
            $that->modified = array($message, $priority, $category, $host);
        });

        $this->adapter->log('test', \LOG_NOTICE, 'closure', 'localhost');
        $this->assertEquals(array('test', \LOG_NOTICE, 'closure', 'localhost'), $this->modified);
    }
}