<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\S3\Command\Bucket;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\GetBucketNotification;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class GetBucketNotificationTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\GetBucketNotification
     */
    public function testGetBucketNotification()
    {
        $command = new GetBucketNotification();
        $this->assertSame($command, $command->setBucket('test'));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'GetBucketNotificationResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $this->assertEquals('http://test.s3.amazonaws.com/?notification', $command->getRequest()->getUrl());
        $this->assertEquals('GET', $command->getRequest()->getMethod());
        $this->assertInstanceOf('SimpleXMLElement', $command->getResult());

        $notification = $command->getResult();
        $this->assertEquals('arn:aws:sns:us-east-1:123456789012:myTopic', (string)$notification->TopicConfiguration->Topic);
        $this->assertEquals('s3:ReducedRedundancyLostObject', (string)$notification->TopicConfiguration->Event);
    }
}