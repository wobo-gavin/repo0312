<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\S3\Command\Bucket;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class PutBucketTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\PutBucket
     */
    public function testPutBucket()
    {
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\PutBucket();
        $command->setBucket('test');
        $command->setAcl('public-read');
        $command->setRegion('us-west-1');

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'PutBucketResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $this->assertEquals('http://test.s3.amazonaws.com/', $command->getRequest()->getUrl());
        $this->assertEquals('PUT', $command->getRequest()->getMethod());

        $this->assertEquals(
            '<?xml version="1.0"?>' . "\n"
            . '<CreateBucketConfiguration xmlns="http://s3.amazonaws.com/doc/2006-03-01/"><LocationConstraint>us-west-1</LocationConstraint></CreateBucketConfiguration>',
            (string)$command->getRequest()->getBody()
        );
    }
}