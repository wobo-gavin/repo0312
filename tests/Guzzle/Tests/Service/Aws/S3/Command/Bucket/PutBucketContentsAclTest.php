<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\S3\Command\Bucket;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Model\Acl;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\PutBucketContentsAcl;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class PutBucketContentsAclTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\PutBucketContentsAcl
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\PutBucketContentsAcl::process
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\PutBucketContentsAcl::setAcl
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\PutBucketContentsAcl::getResult
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ResourceIteratorApplyBatched
     */
    public function testIterativeAclSet()
    {
        $command = new PutBucketContentsAcl();
        $command->setBucket('test');

        $acl = new Acl();
        $acl->getGrantList()->addGrant(S3Client::GRANT_TYPE_GROUP, S3Client::GRANT_ALL, S3Client::GRANT_READ);

        $this->assertSame($command, $command->setAcl($acl));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, array(
            'ListBucketNextMarkerPrefixMarkerResponse',
            'ListBucketResponse',
            'PutObjectAclResponse',
            'PutObjectAclResponse',
            'PutObjectAclResponse',
            'PutObjectAclResponse'

        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $requests = $this->getMockedRequests();

        // Two list buckets followed by deletes for each key found in the results
        $this->assertEquals('GET', $requests[0]->getMethod());
        $this->assertEquals('GET', $requests[1]->getMethod());

        $this->assertEquals('PUT', $requests[2]->getMethod());
        $this->assertEquals('/?acl', $requests[2]->getResourceUri());

        $this->assertEquals('PUT', $requests[3]->getMethod());
        $this->assertEquals('/?acl', $requests[3]->getResourceUri());

        $this->assertEquals('PUT', $requests[4]->getMethod());
        $this->assertEquals('/?acl', $requests[4]->getResourceUri());

        $this->assertEquals('PUT', $requests[5]->getMethod());
        $this->assertEquals('/?acl', $requests[5]->getResourceUri());

        $this->assertEquals(4, $command->getResult()->getIteratedCount());
        $this->assertEquals(1, $command->getResult()->getSentPoolCount());
    }
}