<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\S3\Command\Object;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Object\InitiateMultipartUpload;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class InitiateMultipartUploadTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Object\InitiateMultipartUpload
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Object\AbstractRequestObject
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Object\AbstractRequestObjectPut
     */
    public function testInitiate()
    {
        $command = new InitiateMultipartUpload();
        $command->setBucket('example-bucket')->setKey('example-object');
        
        $command->setRequestHeader('x-amz-test', '123');
        $command->setAcl(S3Client::ACL_PUBLIC_READ);
        $command->setStorageClass('STANDARD');

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'InitiateMultipartUploadResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $request = (string)$command->getRequest();
        $this->assertEquals('http://example-bucket.s3.amazonaws.com/example-object?uploads', $command->getRequest()->getUrl());
        $this->assertEquals('POST', $command->getRequest()->getMethod());

        $this->assertFalse($this->compareHttpHeaders(array(
            'Host' => 'example-bucket.s3.amazonaws.com',
            'Date' => '*',
            'Authorization' => '*',
            'x-amz-test' => '123',
            'x-amz-acl' => 'public-read',
            'x-amz-storage-class' => 'STANDARD',
            'User-Agent' => /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getDefaultUserAgent()
        ), $command->getRequestHeaders()->getAll()));
        
        $this->assertInstanceOf('SimpleXMLElement', $command->getResult());
        $this->assertEquals('example-bucket', (string)$command->getResult()->Bucket);
        $this->assertEquals('example-object', (string)$command->getResult()->Key);
        $this->assertEquals('VXBsb2FkIElEIGZvciA2aWWpbmcncyBteS1tb3ZpZS5tMnRzIHVwbG9hZA', (string)$command->getResult()->UploadId);
    }
}