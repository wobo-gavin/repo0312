<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Curl;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlConstants;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class CurlConstantsTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlConstants
     */
    public function testTranslatesCurlOptionsAndValues()
    {
        $this->assertNotEmpty(CurlConstants::getOptions());
        $this->assertInternalType('array', CurlConstants::getOptions());

        $this->assertNotEmpty(CurlConstants::getValues());
        $this->assertInternalType('array', CurlConstants::getValues());

        $this->assertEquals('CURLOPT_FOLLOWLOCATION', CurlConstants::getOptionName(52));
        $this->assertEquals(52, CurlConstants::getOptionInt('CURLOPT_FOLLOWLOCATION'));
        $this->assertFalse(CurlConstants::getOptionInt('abc'));
        $this->assertFalse(CurlConstants::getOptionName(-100));

        $this->assertEquals('CURLAUTH_DIGEST', CurlConstants::getValueName(2));
        $this->assertEquals(2, CurlConstants::getValueInt('CURLAUTH_DIGEST'));
        $this->assertFalse(CurlConstants::getValueInt('abc'));
        $this->assertFalse(CurlConstants::getValueName(-100));
    }
}