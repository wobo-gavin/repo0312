<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Stream;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\Stream;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\NoSeekStream;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\NoSeekStream
 */
class NoSeekStreamTest extends \PHPUnit_Framework_TestCase
{
    public function testCannotSeek()
    {
        $s = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\StreamInterface')
            ->setMethods(['isSeekable', 'seek'])
            ->getMockForAbstractClass();
        $s->expects($this->never())->method('seek');
        $s->expects($this->never())->method('isSeekable');
        $wrapped = new NoSeekStream($s);
        $this->assertFalse($wrapped->isSeekable());
        $this->assertFalse($wrapped->seek(2));
    }
}
