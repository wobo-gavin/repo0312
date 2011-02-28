<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Sqs\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Sqs\Command\ListQueues;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class ListQueuesTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Sqs\Command\ListQueues
     */
    public function testListQueues()
    {
        $command = new ListQueues();
        $this->assertSame($command, $command->setQueueNamePrefix('test'));
        
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.sqs');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'ListQueuesResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $request = (string) $command->getRequest();
        $response = (string) $command->getResponse();

        $this->assertEquals('GET', $command->getRequest()->getMethod());
        $this->assertContains('GET /?Action=ListQueues&QueueNamePrefix=test', $request);
        $this->assertEquals('sqs.us-east-1.amazonaws.com', $command->getRequest()->getHost());

        $this->assertEquals(array(
            'http://sqs.us-east-1.amazonaws.com/123456789012/testQueue',
            'http://sqs.us-east-1.amazonaws.com/123456789012/testQueue2'
        ), $command->getResult());

        $this->assertEquals('725275ae-0b9b-4762-b238-436d7c65a1ac', $command->getRequestId());
    }
}