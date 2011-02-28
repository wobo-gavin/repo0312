<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\S3\Command\Bucket;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\GetBucketObjectVersions;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class GetBucketObjectVersionsTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Bucket\GetBucketObjectVersions
     */
    public function testGetVersions()
    {
        $command = new GetBucketObjectVersions();
        $this->assertSame($command, $command->setBucket('bucket'));
        $this->assertSame($command, $command->setDelimiter('abc'));
        $this->assertSame($command, $command->setKeyMarker('3'));
        $this->assertSame($command, $command->setMaxKeys(10));
        $this->assertSame($command, $command->setPrefix('my'));
        $this->assertSame($command, $command->setVersionIdMarker(123));
        
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'GetBucketObjectVersionsResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $request = (string)$command->getRequest();
        $this->assertContains('GET /?versions&delimiter=abc&key-marker=3&max-keys=10&prefix=my&version-id-marker=123 HTTP/1.1', $request);
        $this->assertEquals('bucket.s3.amazonaws.com', $command->getRequest()->getHost());
    }
}