<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\S3;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class AbstractClientTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\AbstractClient::getAccessKeyId
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\AbstractClient::getSecretAccessKey
     */
    public function testHoldsAccessIdentifiers()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        /* @var $/* Replaced /* Replaced /* Replaced client */ */ */ /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client */
        $this->assertNotEmpty($/* Replaced /* Replaced /* Replaced client */ */ */->getAccessKeyId());
        $this->assertNotEmpty($/* Replaced /* Replaced /* Replaced client */ */ */->getSecretAccessKey());
    }
}