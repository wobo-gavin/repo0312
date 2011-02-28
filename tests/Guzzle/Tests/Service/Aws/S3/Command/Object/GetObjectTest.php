<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\S3\Command\Object;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Object\GetObject;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class GetObjectTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Object\GetObject
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Object\AbstractRequestObject
     */
    public function testGetObject()
    {
        $command = new GetObject();
        $command->setBucket('test')->setKey('key');
        
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'GetObjectResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $this->assertEquals('http://test.s3.amazonaws.com/key', $command->getRequest()->getUrl());
        $this->assertEquals('GET', $command->getRequest()->getMethod());
        $this->assertEquals('data', $command->getResponse()->getBody(true));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Object\GetObject
     */
    public function testGetObjectTorrent()
    {
        $command = new GetObject();
        $command->setBucket('test')->setKey('key')->setTorrent(true);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'GetObjectResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertEquals('http://test.s3.amazonaws.com/key?torrent', $command->getRequest()->getUrl());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Object\GetObject
     */
    public function testAllowsCustomBody()
    {
        $command = new GetObject();
        $command->setBucket('test')->setKey('key');
        
        $body = EntityBody::factory(fopen('php://temp', 'r+'));
        $command->setResponseBody($body);

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3', true);
        $this->getServer()->enqueue((string) $this->getMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'GetObjectResponse'));
        $/* Replaced /* Replaced /* Replaced client */ */ */->setBaseUrl($this->getServer()->getUrl());
        $/* Replaced /* Replaced /* Replaced client */ */ */->setForcePathHostingBuckets(true);
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $this->assertSame($command->getResponse()->getBody(), $body);
        $this->assertEquals('data', $command->getResponse()->getBody(true));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Object\GetObject
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\S3Exception
     */
    public function testChecksumValidation()
    {
        $command = new GetObject();
        $command->setBucket('test')->setKey('key');
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'GetObjectResponseBadChecksum');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\S3\Command\Object\GetObject
     */
    public function testDisableChecksumValidation()
    {
        $command = new GetObject();
        $command->setBucket('test')->setKey('key');
        $command->disableChecksumValidation();
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.s3');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'GetObjectResponseBadChecksum');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
    }
}