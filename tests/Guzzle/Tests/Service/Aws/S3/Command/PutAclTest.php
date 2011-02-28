<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\S3\Command;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class PutAclTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    private $_acl;

    public function setUp()
    {
        $this->_acl = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Model\Acl();
        $this->_acl->getGrantList()->addGrant('CanonicalUser', '8a6925ce4adf588a453214a379004fef', 'FULL_CONTROL');
        $this->_acl->setOwnerId('123');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\PutAcl
     */
    public function testPutObjectAcl()
    {
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\PutAcl();
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $command->setBucket('test')->setKey('key')->setAcl($this->_acl);
        $command->setVersionId('1234');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'PutObjectAclResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $this->assertEquals('PUT', $command->getRequest()->getMethod());
        $this->assertEquals('http://test.s3.amazonaws.com/key?acl&versionId=1234', $command->getRequest()->getUrl());

        $request = (string)$command->getRequest();
        $this->assertContains('PUT /key?acl&versionId=1234 HTTP/1.1', $request);
        $this->assertContains('<AccessControlPolicy><Owner><ID>123</ID></Owner><AccessControlList><Grant><Grantee xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="CanonicalUser"><ID>8a6925ce4adf588a453214a379004fef</ID></Grantee><Permission>FULL_CONTROL</Permission></Grant></AccessControlList></AccessControlPolicy>', $request);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\PutAcl
     */
    public function testPutBucketAcl()
    {
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\PutAcl();
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $command->setBucket('test')->setAcl($this->_acl);
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'PutObjectAclResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $this->assertEquals('PUT', $command->getRequest()->getMethod());
        $this->assertEquals('http://test.s3.amazonaws.com/?acl', $command->getRequest()->getUrl());

        $request = (string)$command->getRequest();
        $this->assertContains('PUT /?acl HTTP/1.1', $request);
        $this->assertContains('<AccessControlPolicy><Owner><ID>123</ID></Owner><AccessControlList><Grant><Grantee xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="CanonicalUser"><ID>8a6925ce4adf588a453214a379004fef</ID></Grantee><Permission>FULL_CONTROL</Permission></Grant></AccessControlList></AccessControlPolicy>', $request);
    }
}