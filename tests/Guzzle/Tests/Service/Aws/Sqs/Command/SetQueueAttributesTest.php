<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Aws\Sqs\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Sqs\Command\SetQueueAttributes;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class SetQueueAttributesTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Aws\Sqs\Command\SetQueueAttributes
     */
    public function testSetQueueAttributes()
    {
        $command = new SetQueueAttributes();
        $this->assertSame($command, $command->setQueueUrl('http://sqs.us-east-1.amazonaws.com/123456789012/testQueue'));
        $this->assertSame($command, $command->addAttribute(SetQueueAttributes::VISIBILITY_TIMEOUT, 12));
        $this->assertSame($command, $command->addAttribute(SetQueueAttributes::MAXIMUM_MESSAGE_SIZE, 8192));

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.sqs');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'SetQueueAttributesResponse');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $request = (string) $command->getRequest();
        $response = (string) $command->getResponse();

        $this->assertEquals('GET', $command->getRequest()->getMethod());
        $this->assertContains('GET /123456789012/testQueue?Action=SetQueueAttributes', $request);

        $this->assertEquals('VisibilityTimeout', $command->getRequest()->getQuery()->get('Attribute.1.Name'));
        $this->assertEquals('12', $command->getRequest()->getQuery()->get('Attribute.1.Value'));
        $this->assertEquals('MaximumMessageSize', $command->getRequest()->getQuery()->get('Attribute.2.Name'));
        $this->assertEquals('8192', $command->getRequest()->getQuery()->get('Attribute.2.Value'));

        $this->assertEquals('sqs.us-east-1.amazonaws.com', $command->getRequest()->getHost());
        $this->assertEquals('/123456789012/testQueue', $command->getRequest()->getPath());
        $this->assertEquals('e5cca473-4fc0-4198-a451-8abb94d02c75', $command->getRequestId());
        $this->assertInstanceOf('SimpleXMLElement', $command->getResult());
    }
}