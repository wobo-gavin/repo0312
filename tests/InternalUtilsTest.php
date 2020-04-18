<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Test;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\/* Replaced /* Replaced /* Replaced Psr7 */ */ */;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Utils;
use PHPUnit\Framework\TestCase;

class InternalUtilsTest extends TestCase
{
    public function testCurrentTime()
    {
        self::assertGreaterThan(0, Utils::currentTime());
    }

    public function testIdnConvert()
    {
        $uri = /* Replaced /* Replaced /* Replaced Psr7 */ */ */\uri_for('https://яндекс.рф/images');
        $uri = Utils::idnUriConvert($uri);
        self::assertSame('xn--d1acpjx3f.xn--p1ai', $uri->getHost());
    }
}
