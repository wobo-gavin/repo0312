<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\S3\Command\Bucket;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class GetBucketLocationTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\GetBucketLocation
     */
    public function testGetBucketLocation()
    {
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\GetBucketLocation();
        $command->setBucket('test');
        
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'GetBucketLocationResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $this->assertEquals('http://test.s3.amazonaws.com/?location', $command->getRequest()->getUrl());
        $this->assertEquals('GET', $command->getRequest()->getMethod());
        $this->assertEquals('EU', $command->getResult());
    }
}