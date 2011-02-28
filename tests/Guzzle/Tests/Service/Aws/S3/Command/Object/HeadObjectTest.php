<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\S3\Command\Object;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class HeadObjectTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Object\HeadObject
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Object\AbstractRequestObject
     */
    public function testHeadObject()
    {
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Object\HeadObject();
        $command->setBucket('test')->setKey('key');

        $command->setRange('bytes=500-999');
        $command->setIfMatch('abcd');
        $command->setIfNoneMatch('efghi');
        $command->setIfModifiedSince('Sat, 29 Oct 1994 19:43:31 GMT');
        $command->setIfUnmodifiedSince('Sat, 29 Oct 1994 19:43:31 GMT');
        $command->setRequestHeader('x-amz-test', '123');
        $command->setVersionId('123');

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'HeadObjectResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $this->assertEquals('http://test.s3.amazonaws.com/key?versionId=123', $command->getRequest()->getUrl());
        $this->assertEquals('HEAD', $command->getRequest()->getMethod());

        $this->assertFalse($this->compareHttpHeaders(array(
            'Host' => 'test.s3.amazonaws.com',
            'User-Agent' => /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getDefaultUserAgent(),
            'Date' => '*',
            'Authorization' => '*',
            'Range' => 'bytes=500-999',
            'If-Match' => 'abcd',
            'If-None-Match' => 'efghi',
            'If-Unmodified-Since' => 'Sat, 29 Oct 1994 19:43:31 GMT',
            'If-Modified-Since' => 'Sat, 29 Oct 1994 19:43:31 GMT',
            'x-amz-test' => '123'
        ), $command->getRequest()->getHeaders()->getAll()));
    }
}