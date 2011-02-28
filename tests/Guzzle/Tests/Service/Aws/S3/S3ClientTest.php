<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\S3;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class S3ClientTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * Data provider for testing if a bucket name is valid
     * 
     * @return array
     */
    public function bucketNameProvider()
    {
        return array(
            array('.bucket', false),
            array('bucket.', false),
            array('192.168.1.1', false),
            array('test@42!@$5_', false),
            array('12', false),
            array('bucket_name', false),
            array('bucket-name', true),
            array('bucket', true),
            array('my.bucket.com', true)
        );
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client::isValidBucketName
     * @dataProvider bucketNameProvider
     */
    public function testIsValidBucketName($bucketName, $isValid)
    {
        $this->assertEquals($isValid, s3Client::isValidBucketName($bucketName));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client::setForcePathHostingBuckets
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client::isPathHostingBuckets
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client::getS3Request
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client::getRequest
     */
    public function testAllowsPathHostingForOldBuckets()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        /* @var $/* Replaced /* Replaced /* Replaced client */ */ */ /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client */
        $this->assertFalse($/* Replaced /* Replaced /* Replaced client */ */ */->isPathHostingBuckets());
        $this->assertSame($/* Replaced /* Replaced /* Replaced client */ */ */, $/* Replaced /* Replaced /* Replaced client */ */ */->setForcePathHostingBuckets(true));
        $this->assertTrue($/* Replaced /* Replaced /* Replaced client */ */ */->isPathHostingBuckets());

        // Test using path hosting for older buckets
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->getS3Request('GET', 'test', 'key');
        $this->assertEquals('http://s3.amazonaws.com/test/key', $request->getUrl());
        $this->assertEquals('s3.amazonaws.com', $request->getHost());

        // Test using bucket subdomain hosting
        $this->assertSame($/* Replaced /* Replaced /* Replaced client */ */ */, $/* Replaced /* Replaced /* Replaced client */ */ */->setForcePathHostingBuckets(false));
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->getS3Request('GET', 'test', 'key');
        $this->assertEquals('http://test.s3.amazonaws.com/key', $request->getUrl());
        $this->assertEquals('test.s3.amazonaws.com', $request->getHost());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client::getS3Request
     */
    public function testGetS3Request()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        /* @var $/* Replaced /* Replaced /* Replaced client */ */ */ /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client */
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->getS3Request('GET');
        $this->assertEquals('http://s3.amazonaws.com/', $request->getUrl());

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->getS3Request('GET', 'test');
        $this->assertEquals('http://test.s3.amazonaws.com/', $request->getUrl());

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->getS3Request('GET', 'test', 'key');
        $this->assertEquals('http://test.s3.amazonaws.com/key', $request->getUrl());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client::getSignedUrl
     * @expectedException InvalidArgumentException
     */
    public function testGetSignedUrlThrowsExceptionWhenRequesterPaysAndTorrent()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        /* @var $/* Replaced /* Replaced /* Replaced client */ */ */ /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client */
        $url = $/* Replaced /* Replaced /* Replaced client */ */ */->getSignedUrl('test', 'key', 60, 'static.test.com', true, true);
        echo $url;
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client::getSignedUrl
     */
    public function testGetSignedUrl()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        /* @var $/* Replaced /* Replaced /* Replaced client */ */ */ /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client */
        $url = $/* Replaced /* Replaced /* Replaced client */ */ */->getSignedUrl('test', 'test.zip', 60, false, false, false);
        $this->assertContains('&Expires=', $url);
        $this->assertContains('&Signature=', $url);
        $this->assertContains('http://test.s3.amazonaws.com/test.zip?AWSAccessKeyId=', $url);

        $url = $/* Replaced /* Replaced /* Replaced client */ */ */->getSignedUrl('images.test.com', 'test.zip', 60, true, false, false);
        $this->assertContains('http://images.test.com/test.zip?AWSAccessKeyId=', $url);

        $url = $/* Replaced /* Replaced /* Replaced client */ */ */->getSignedUrl('images.test.com', 'test.zip', 60, false, true, false);
        $this->assertContains('&torrent&', $url);

        $url = $/* Replaced /* Replaced /* Replaced client */ */ */->getSignedUrl('images.test.com', 'test.zip', 60, false, false, true);
        $this->assertContains('&x-amz-request-payer=requester&', $url);
    }
}