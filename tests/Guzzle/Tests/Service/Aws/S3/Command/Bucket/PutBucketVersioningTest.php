<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\S3\Command\Bucket;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Client;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class PutBucketVersioningTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\PutBucketVersioning
     */
    public function testEnableVersioning()
    {
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\PutBucketVersioning();
        $this->assertSame($command, $command->setBucket('test'));
        $this->assertSame($command, $command->setStatus(true));
        $this->assertSame($command, $command->setMfaDelete(true));
        $this->assertSame($command, $command->setMfaHeader('abc 123'));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'PutBucketVersioningResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $request = (string)$command->getRequest();
        $this->assertContains('PUT /?versioning HTTP/1.1', $request);
        $this->assertContains('Host: test.s3.amazonaws.com', $request);
        $this->assertContains('x-amz-mfa: abc 123', $request);
        $this->assertContains('<VersioningConfiguration xmlns="http://s3.amazonaws.com/doc/2006-03-01/"><Status>Enabled</Status><MfaDelete>Enabled</MfaDelete></VersioningConfiguration>', $request);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\PutBucketVersioning
     */
    public function testDisableVersioning()
    {
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\PutBucketVersioning();
        $this->assertSame($command, $command->setBucket('test'));
        $this->assertSame($command, $command->setStatus(false));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'PutBucketVersioningResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $this->assertEquals('<VersioningConfiguration xmlns="http://s3.amazonaws.com/doc/2006-03-01/"><Status>Suspended</Status></VersioningConfiguration>', (string)$command->getRequest()->getBody());
    }
}