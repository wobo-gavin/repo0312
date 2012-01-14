<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\NullObject;

class NullObjectTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\NullObject
     */
    public function testAll()
    {
        $nullObject = new NullObject();
        $this->assertNull($nullObject->isItNull());
        isset($nullObject->isNull);
        $this->assertNull($nullObject->isNull);
        $nullObject->isNull = 10;
        unset($nullObject->isNull);
        $this->assertNull($nullObject->offsetGet('a'));

        $nullObject['a'] = '123';
        $this->assertFalse(isset($nullObject['a']));
        $this->assertNull($nullObject['a']);
        $this->assertNull($nullObject->offsetUnset('a'));

        $this->assertNull($nullObject->count());
        $this->assertNull($nullObject->key());
        $this->assertNull($nullObject->next());
        $this->assertNull($nullObject->rewind());
        $this->assertNull($nullObject->valid());
        $this->assertNull($nullObject->current());
    }
}