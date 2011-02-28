<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\S3\Command;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class GetAclTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\GetAcl
     */
    public function testGetObjectAcl()
    {
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\GetAcl();
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $command->setBucket('test');
        $command->setKey('key');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'GetObjectAclResponse');

        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $this->assertEquals('http://test.s3.amazonaws.com/key?acl', $command->getRequest()->getUrl());

        $acl = $command->getResult();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Aws\\S3\\Model\\Acl', $acl);

        $this->assertTrue($acl->getGrantList()->hasGrant('CanonicalUser', '8a6925ce4adf588a453214a379004fef'));
        $this->assertEquals('8a6925ce4adf588a4532aa379004fef', $acl->getOwnerId());
        $this->assertEquals('mtd@amazon.com', $acl->getOwnerDisplayName());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\GetAcl
     */
    public function testGetBucketAcl()
    {
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\GetAcl();
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $command->setBucket('test');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'GetBucketAclResponse');

        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $this->assertEquals('http://test.s3.amazonaws.com/?acl', $command->getRequest()->getUrl());

        $acl = $command->getResult();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Aws\\S3\\Model\\Acl', $acl);

        $this->assertTrue($acl->getGrantList()->hasGrant('CanonicalUser', '8a6925ce4adf57f21c32aa379004fef'));
        $this->assertEquals('8a6925ce4adee97f21c32aa379004fef', $acl->getOwnerId());
        $this->assertEquals('CustomersName@amazon.com', $acl->getOwnerDisplayName());
    }
}