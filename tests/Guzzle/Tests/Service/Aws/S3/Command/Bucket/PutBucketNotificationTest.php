<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\S3\Command\Bucket;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\PutBucketNotification;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class PutBucketNotificationTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\PutBucketNotification
     */
    public function testPutNotification()
    {
        $notification =
        '<NotificationConfiguration>' .
            '<TopicConfiguration>' . 
                '<Topic>arn:aws:sns:us-east-1:123456789012:myTopic</Topic>' .
                '<Event>s3:ReducedRedundancyLostObject</Event>' . 
            '</TopicConfiguration>' . 
        '</NotificationConfiguration>';

        $xml = new \SimpleXMLElement($notification);

        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\PutBucketNotification();
        $this->assertSame($command, $command->setBucket('test'));
        $this->assertSame($command, $command->setNotification($xml));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'PutBucketNotificationResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $request = (string)$command->getRequest();
        $this->assertContains('PUT /?notification HTTP/1.1', $request);
        $this->assertContains('Host: test.s3.amazonaws.com', $request);
        $this->assertContains($notification, $request);
        
        $this->assertTrue($command->getResponse()->hasHeader('x-amz-sns-test-message-id'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\PutBucketNotification
     */
    public function testPutNotificationOff()
    {
        $notification = '<NotificationConfiguration />';

        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\PutBucketNotification();
        $this->assertSame($command, $command->setBucket('test'));
        $this->assertSame($command, $command->setNotification($notification));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'PutBucketNotificationOffResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $request = (string)$command->getRequest();
        $this->assertContains('PUT /?notification HTTP/1.1', $request);
        $this->assertContains('Host: test.s3.amazonaws.com', $request);
        $this->assertContains($notification, $request);

        $this->assertFalse($command->getResponse()->hasHeader('x-amz-sns-test-message-id'));
    }
}