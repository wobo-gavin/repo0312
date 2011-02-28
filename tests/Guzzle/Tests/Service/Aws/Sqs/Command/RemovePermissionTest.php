<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Sqs\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Sqs\Command\RemovePermission;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class RemovePermissionTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Sqs\Command\RemovePermission
     */
    public function testRemovePermission()
    {
        $command = new RemovePermission();
        $this->assertSame($command, $command->setQueueUrl('http://sqs.us-east-1.amazonaws.com/226005815177/michael'));
        $this->assertSame($command, $command->setLabel('testLabel'));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.sqs');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'RemovePermissionResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $request = (string) $command->getRequest();
        $response = (string) $command->getResponse();

        $this->assertEquals('GET', $command->getRequest()->getMethod());
        $this->assertContains('GET /226005815177/michael?Action=RemovePermission&Label=testLabel', $request);
        $this->assertEquals('sqs.us-east-1.amazonaws.com', $command->getRequest()->getHost());
        $this->assertEquals('/226005815177/michael', $command->getRequest()->getPath());
        $this->assertEquals('f8bdb362-6616-42c0-977a-ce9a8bcce3bb', $command->getRequestId());
    }
}