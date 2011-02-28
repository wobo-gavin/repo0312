<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Sqs\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Sqs\Command\AddPermission;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class AddPermissionTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Sqs\Command\AddPermission
     */
    public function testAddPermission()
    {
        $command = new AddPermission();
        $this->assertSame($command, $command->setQueueUrl('http://sqs.us-east-1.amazonaws.com/123456789012/testQueue'));
        $this->assertSame($command, $command->addPermission('123', 'SendMessage'));
        $this->assertSame($command, $command->addPermission('123', 'ReceiveMessage'));
        $this->assertSame($command, $command->setLabel('Test'));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.sqs');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'AddPermissionResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $request = (string) $command->getRequest();
        $response = (string) $command->getResponse();

        $this->assertEquals('GET', $command->getRequest()->getMethod());
        $this->assertContains('GET /123456789012/testQueue?Action=AddPermission', $request);

        $this->assertEquals('123', $command->getRequest()->getQuery()->get('AWSAccountId.1'));
        $this->assertEquals('123', $command->getRequest()->getQuery()->get('AWSAccountId.2'));
        $this->assertEquals('SendMessage', $command->getRequest()->getQuery()->get('ActionName.1'));
        $this->assertEquals('ReceiveMessage', $command->getRequest()->getQuery()->get('ActionName.2'));
        $this->assertEquals('Test', $command->getRequest()->getQuery()->get('Label'));

        $this->assertEquals('sqs.us-east-1.amazonaws.com', $command->getRequest()->getHost());
        $this->assertEquals('/123456789012/testQueue', $command->getRequest()->getPath());
        $this->assertEquals('9a285199-c8d6-47c2-bdb2-314cb47d599d', $command->getRequestId());
        $this->assertInstanceOf('SimpleXMLElement', $command->getResult());
    }
}