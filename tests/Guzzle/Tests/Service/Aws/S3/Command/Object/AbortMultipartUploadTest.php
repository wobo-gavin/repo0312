<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\S3\Command\Object;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Object\AbortMultipartUpload;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class AbortMultipartUploadTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Object\AbortMultipartUpload
     */
    public function testAbort()
    {
        $command = new AbortMultipartUpload();
        $this->assertSame($command, $command->setBucket('test'));
        $this->assertSame($command, $command->setKey('key'));
        $this->assertSame($command, $command->setUploadId('123'));
        
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'DefaultResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $this->assertEquals('http://test.s3.amazonaws.com/key?uploadId=123', $command->getRequest()->getUrl());
        $this->assertEquals('DELETE', $command->getRequest()->getMethod());
    }
}