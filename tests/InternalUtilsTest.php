<?php

namespace /* Replaced /* Replaced Guzzle */ */Http\Test;

use /* Replaced /* Replaced Guzzle */ */Http\/* Replaced /* Replaced Psr7 */ */;
use /* Replaced /* Replaced Guzzle */ */Http\Utils;
use PHPUnit\Framework\TestCase;

class InternalUtilsTest extends TestCase
{
    public function testCurrentTime()
    {
        self::assertGreaterThan(0, Utils::currentTime());
    }

    /**
     * @requires extension idn
     */
    public function testIdnConvert()
    {
        $uri = /* Replaced /* Replaced Psr7 */ */\Utils::uriFor('https://яндекс.рф/images');
        $uri = Utils::idnUriConvert($uri);
        self::assertSame('xn--d1acpjx3f.xn--p1ai', $uri->getHost());
    }
}
