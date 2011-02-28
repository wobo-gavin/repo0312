<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\S3\Command\Bucket;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class GetBucketLoggingTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\GetBucketLogging
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Model\BucketLoggingStatus
     */
    public function testGetBucketLogging()
    {
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\GetBucketLogging();
        $command->setBucket('test');

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'GetBucketLoggingResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $this->assertEquals('http://test.s3.amazonaws.com/?logging', $command->getRequest()->getUrl());
        $this->assertEquals('GET', $command->getRequest()->getMethod());
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Aws\\S3\\Model\\BucketLoggingStatus', $command->getResult());

        $status = $command->getResult();

        $this->assertInstanceOf('SimpleXMLElement', $status->getXml());
        $this->assertFalse($status->isLoggingEnabled());
        $this->assertFalse($status->getTargetBucket());
        $this->assertFalse($status->getTargetPrefix());
        $this->assertEquals(array(), $status->getGrants());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Model\BucketLoggingStatus
     */
    public function testGetBucketLoggingEnabled()
    {
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\GetBucketLogging();
        $command->setBucket('test');
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'GetBucketLoggingEnabledResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $status = $command->getResult();
        $this->assertInstanceOf('SimpleXMLElement', $status->getXml());
        $this->assertTrue($status->isLoggingEnabled());
        $this->assertEquals('mybucketlogs', $status->getTargetBucket());
        $this->assertEquals('mybucket-access_log-/', $status->getTargetPrefix());
        $this->assertEquals(array(
            array('user@company.com', 'READ')
        ), $status->getGrants());
    }
}