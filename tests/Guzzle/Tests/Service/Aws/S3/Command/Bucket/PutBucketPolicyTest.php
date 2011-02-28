<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\S3\Command\Bucket;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\PutBucketPolicy;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class PutBucketPolicyTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\PutBucketPolicy
     */
    public function testPutBucketPolicy()
    {
        $policy = array(
            'Version' => '2008-10-17',
            'Id' => 'aaaa-bbbb-cccc-dddd',
            'Statement' => array(
                0 => array(
                    'Effect' => 'Deny',
                    'Sid' => '1',
                    'Principal' => array(
                        'AWS' => array('1-22-333-4444', '3-55-678-9100'),
                    ),
                    'Action' => array('s3:*',),
                    'Resource' => 'arn:aws:s3:::bucket/*',
                )
            )
        );

        $encodedPolicy = json_encode($policy);
        
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\PutBucketPolicy();
        $this->assertSame($command, $command->setBucket('test'));
        $this->assertSame($command, $command->setPolicy($policy));
        
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'PutBucketPolicyResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $request = (string)$command->getRequest();
        $this->assertContains('PUT /?policy HTTP/1.1', $request);
        $this->assertContains('Host: test.s3.amazonaws.com', $request);
        $this->assertContains($encodedPolicy, $request);
    }
}