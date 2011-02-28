<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Unfuddle\Command;

/**
 * @group Unfuddle
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class MessageTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Messages\GetMessage
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\AbstractUnfuddleCommand
     */
    public function testGetMessage()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.unfuddle');
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Messages\GetMessage();
        $command->setProjectId(1);
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'message.get_messages');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertContains('unfuddle.com/api/v1/projects/1/messages', $command->getRequest()->getUrl());

        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Messages\GetMessage();
        $command->setProjectId(1)->setId(1);
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'message.get_messages');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertContains('unfuddle.com/api/v1/projects/1/messages/1', $command->getRequest()->getUrl());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Messages\UpdateMessage
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Messages\AbstractMessageBodyCommand
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\AbstractUnfuddleBodyCommand
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\AbstractUnfuddleCommand
     */
    public function testUpdateMessage()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.unfuddle');
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Messages\UpdateMessage();
        $command->setProjectId(1);
        $command->setId(1);
        $command->setBody('Testing part deux')
                ->setTitle('Testing')
                ->setCategories(array(1));

        // Make sure the same property can be set more than once
        $command->setTitle('Testing - Reloaded');

        // We don't care about the response to this command, so just use this mock
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'message.get_messages');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertContains('PUT /api/v1/projects/1/messages/1 HTTP/1.1', (string)$command->getRequest());
        $this->assertEquals('<?xml version="1.0"?>' . "\n" . '<message><body>Testing part deux</body><title>Testing - Reloaded</title><categories><category id="1"></category></categories></message>', trim((string)$command->getRequest()->getBody()));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Messages\CreateMessage
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Messages\AbstractMessageBodyCommand
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\AbstractUnfuddleBodyCommand
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\AbstractUnfuddleCommand
     */
    public function testCreateMessage()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.unfuddle');
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Messages\CreateMessage();
        $this->assertEquals($command, $command->setTitle('Test create'));
        $this->assertEquals($command, $command->setBody('body'));
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'message.create_message');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $message = (string)$command->getRequest();
        $this->assertContains('POST /api/v1/messages HTTP/1.1', $message);
        $this->assertContains('<message><title>Test create</title><body>body</body></message>', $message);
        $this->assertContains('Content-Type: application/xml', $message);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Messages\DeleteMessage
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\AbstractUnfuddleCommand
     */
    public function testDeleteMessage()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.unfuddle');
        $command = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Messages\DeleteMessage();
        $this->assertEquals($command, $command->setId(1));
        // We don't care about the response, so just set anything
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'message.create_message');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $message = (string)$command->getRequest();
        $this->assertContains('DELETE /api/v1/messages/1 HTTP/1.1', $message);
    }
}