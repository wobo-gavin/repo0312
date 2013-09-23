<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Stream;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\Stream;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\NoSeekStream;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\NoSeekStream
 */
class NoSeekStreamTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testCannotSeek()
    {
        $s = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamInterface')
            ->setMethods(['isSeekable', 'seek'])
            ->getMockForAbstractClass();
        $s->expects($this->never())->method('seek');
        $s->expects($this->never())->method('isSeekable');
        $wrapped = new NoSeekStream($s);
        $this->assertFalse($wrapped->isSeekable());
        $this->assertFalse($wrapped->seek(2));
    }
}
