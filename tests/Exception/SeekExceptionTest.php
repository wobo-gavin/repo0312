<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Exception;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\SeekException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */;

class SeekExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testHasStream()
    {
        $s = /* Replaced /* Replaced /* Replaced Psr7 */ */ */\stream_for('foo');
        $e = new SeekException($s, 10);
        $this->assertSame($s, $e->getStream());
        $this->assertContains('10', $e->getMessage());
    }
}