<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\S3\Command\Bucket;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\DeleteBucketPolicy;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class DeleteBucketPolicyTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\DeleteBucketPolicy
     */
    public function testDeleteBucketPolicy()
    {
        $command = new DeleteBucketPolicy();
        $this->assertSame($command, $command->setBucket('test'));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'DeleteBucketPolicyResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        // Ensure that the DELETE request was sent to the policy sub resource
        $this->assertEquals('http://test.s3.amazonaws.com/?policy', $command->getRequest()->getUrl());
        $this->assertEquals('DELETE', $command->getRequest()->getMethod());

        // Check the raw HTTP request message
        $request = explode("\r\n", (string) $command->getRequest());
        $this->assertEquals('DELETE /?policy HTTP/1.1', $request[0]);
        $this->assertEquals('User-Agent: ' . /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getDefaultUserAgent(), $request[1]);
        $this->assertEquals('Host: test.s3.amazonaws.com', $request[2]);
        $this->assertContains("Date: ", $request[3]);
        $this->assertContains("Authorization: ", $request[4]);
    }
}