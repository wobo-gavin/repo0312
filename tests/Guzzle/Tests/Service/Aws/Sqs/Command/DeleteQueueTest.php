<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Sqs\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Sqs\Command\DeleteQueue;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class DeleteQueueTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Sqs\Command\DeleteQueue
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Sqs\Command\AbstractQueueUrlCommand
     */
    public function testDeleteQueue()
    {
        $command = new DeleteQueue();
        $this->assertSame($command, $command->setQueueUrl('http://sqs.us-east-1.amazonaws.com/226005815177/michael'));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.sqs');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'DeleteQueueResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $request = (string) $command->getRequest();
        $response = (string) $command->getResponse();

        $this->assertEquals('GET', $command->getRequest()->getMethod());
        $this->assertContains('GET /226005815177/michael?Action=DeleteQueue', $request);
        $this->assertEquals('sqs.us-east-1.amazonaws.com', $command->getRequest()->getHost());
        $this->assertEquals('/226005815177/michael', $command->getRequest()->getPath());
        $this->assertEquals('f6a89b95-5f0f-44b5-ab58-7cfebd318361', $command->getRequestId());
    }
}