<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\S3\Command\Bucket;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\ClearBucket;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class ClearBucketTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\ClearBucket
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ResourceIteratorApplyBatched
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Model\BucketIterator
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ResourceIterator
     */
    public function testClearBucket()
    {
        $command = new ClearBucket();
        $command->setBucket('test');
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, array(
            'ListBucketNextMarkerPrefixMarkerResponse',
            'ListBucketResponse',
            'DeleteObjectResponse',
            'DeleteObjectResponse',
            'DeleteObjectResponse',
            'DeleteObjectResponse'

        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $requests = $this->getMockedRequests();

        // Two list buckets followed by deletes for each key found in the results
        $this->assertEquals('GET', $requests[0]->getMethod());
        $this->assertEquals('GET', $requests[1]->getMethod());
        
        $this->assertEquals('DELETE', $requests[2]->getMethod());
        $this->assertEquals('/Nelson', $requests[2]->getPath());
        
        $this->assertEquals('DELETE', $requests[3]->getMethod());
        $this->assertEquals('/Neo', $requests[3]->getPath());
        
        $this->assertEquals('DELETE', $requests[4]->getMethod());
        $this->assertEquals('/my-image.jpg', $requests[4]->getPath());
        
        $this->assertEquals('DELETE', $requests[5]->getMethod());
        $this->assertEquals('/my-third-image.jpg', $requests[5]->getPath());

        $this->assertEquals(4, $command->getResult()->getIteratedCount());
        $this->assertEquals(1, $command->getResult()->getSentPoolCount());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\ClearBucket
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ResourceIteratorApplyBatched
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Model\BucketIterator
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ResourceIterator
     */
    public function testClearBucketUsesLimit()
    {
        $command = new ClearBucket();
        $command->setBucket('test');
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $command->setPerBatch(2);

        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, array(
            'ListBucketNextMarkerPrefixMarkerResponse',
            'ListBucketResponse',
            'DeleteObjectResponse',
            'DeleteObjectResponse',
            'DeleteObjectResponse',
            'DeleteObjectResponse'
        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $this->assertEquals(4, $command->getResult()->getIteratedCount());
        $this->assertEquals(2, $command->getResult()->getSentPoolCount());
    }
}