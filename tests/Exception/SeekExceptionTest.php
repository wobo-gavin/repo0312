<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Exception;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\SeekException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */;
use PHPUnit\Framework\TestCase;

class SeekExceptionTest extends TestCase
{
    public function testHasStream()
    {
        $s = /* Replaced /* Replaced /* Replaced Psr7 */ */ */\stream_for('foo');
        $e = new SeekException($s, 10);
        self::assertSame($s, $e->getStream());
        self::assertContains('10', $e->getMessage());
    }
}
