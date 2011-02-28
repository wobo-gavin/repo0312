<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\NullIterator;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class NullIteratorTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\NullIterator
     */
    public function testAll()
    {
        $nullObject = new NullIterator();
        $this->assertNull($nullObject->count());
        $this->assertNull($nullObject->key());
        $this->assertNull($nullObject->next());
        $this->assertNull($nullObject->rewind());
        $this->assertNull($nullObject->valid());
        $this->assertNull($nullObject->current());
    }
}