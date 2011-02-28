<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class /* Replaced /* Replaced /* Replaced Guzzle */ */ */Test extends /* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */
     */
    public function testGetDefaultUserAgent()
    {
        $version = curl_version();
        $agent = sprintf('/* Replaced /* Replaced /* Replaced Guzzle */ */ *//%s (Language=PHP/%s; curl=%s; Host=%s)', /* Replaced /* Replaced /* Replaced Guzzle */ */ */::VERSION, \PHP_VERSION, $version['version'], $version['host']);

        $this->assertEquals($agent, /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getDefaultUserAgent());

        // Get it from cache this time
        $this->assertEquals($agent, /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getDefaultUserAgent());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */::getHttpDate
     */
    public function testGetHttpDate()
    {
        $fmt = 'D, d M Y H:i:s \G\M\T';

        $this->assertEquals(gmdate($fmt), /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getHttpDate('now'));
        $this->assertEquals(gmdate($fmt), /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getHttpDate(strtotime('now')));
        $this->assertEquals(gmdate($fmt, strtotime('+1 day')), /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getHttpDate('+1 day'));
    }
}