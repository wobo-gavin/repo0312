<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Sqs\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Sqs\Command\CreateQueue;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class CreateQueueTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Sqs\Command\CreateQueue
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Sqs\Command\AbstractCommand
     */
    public function testCreateQueue()
    {
        $command = new CreateQueue();
        $this->assertSame($command, $command->setQueueName('testQueue'));
        $this->assertSame($command, $command->setDefaultVisibilityTimeout(20));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.sqs');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'CreateQueueResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $request = (string) $command->getRequest();
        $response = (string) $command->getResponse();

        $this->assertEquals('GET', $command->getRequest()->getMethod());
        $this->assertContains('GET /?Action=CreateQueue&QueueName=testQueue&DefaultVisibilityTimeout=20&Timestamp=', $request);
        $this->assertEquals('sqs.us-east-1.amazonaws.com', $command->getRequest()->getHost());

        $this->assertEquals('http://sqs.us-east-1.amazonaws.com/123456789012/testQueue', $command->getResult());
        $this->assertEquals('7a62c49f-347e-4fc4-9331-6e8e7a96aa73', $command->getRequestId());
    }
}