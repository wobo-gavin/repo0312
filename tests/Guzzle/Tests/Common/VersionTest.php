<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Version;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Version
 */
class VersionTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @expectedException \PHPUnit_Framework_Error_Deprecated
     */
    public function testEmitsWarnings()
    {
        Version::$emitWarnings = true;
        Version::warn('testing!');
    }

    public function testCanSilenceWarnings()
    {
        Version::$emitWarnings = false;
        Version::warn('testing!');
        Version::$emitWarnings = true;
    }
}
