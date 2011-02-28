<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Unfuddle\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Attachments\DeleteAttachment;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Attachments\DownloadAttachment;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Attachments\UploadAttachment;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Attachments\UpdateAttachment;

/**
 * @group Unfuddle
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class AttachmentTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Attachments\DeleteAttachment
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Attachments\AbstractAttachmentCommand
     */
    public function testDeleteAttachment()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.unfuddle');
        $command = new DeleteAttachment();
        $command->setProjectId(1)->setId(1)->setTypeId('messages', 1);
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'default');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertContains('DELETE /api/v1/projects/1/messages/1/attachments/1 HTTP/1.1', (string)$command->getRequest());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Attachments\DownloadAttachment
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Attachments\AbstractAttachmentCommand
     */
    public function testDownloadAttachment()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.unfuddle');
        $command = new DownloadAttachment();
        $command->setProjectId(1)->setId(1);
        $this->assertEquals($command, $command->setTypeId('messages', 1));
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'default');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $this->assertContains('GET /api/v1/projects/1/messages/1/attachments/1/download HTTP/1.1', (string)$command->getRequest());
    }

    /**
     *@covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Attachments\UploadAttachment
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Attachments\AbstractAttachmentCommand
     */
    public function testUploadAttachment()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.unfuddle');
        $command = new UploadAttachment();
        $command->setProjectId(1);
        $this->assertEquals($command, $command->setTypeId('messages', 1));
        $this->assertEquals($command, $command->setBody('body'));
        $this->assertEquals($command, $command->setContentType('text/plain'));
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'default');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $message = (string)$command->getRequest();
        $this->assertContains('POST /api/v1/projects/1/messages/1/attachments/upload HTTP/1.1', $message);
        $this->assertEquals('body', (string)$command->getRequest()->getBody());
        $this->assertEquals('text/plain', $command->getRequest()->getHeader('Content-Type'));
        
        // Now try using a comment
        $command = new UploadAttachment(array(
            'projects' => 1,
            'type' => 'tickets_comments',
            'type_id' => 1,
            'content_type' => 'text/plain',
            'body' => 'body'
        ));
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'default');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $message = (string)$command->getRequest();
        $this->assertContains('POST /api/v1/projects/1/tickets/comments/1/attachments/upload HTTP/1.1', $message);
        $this->assertEquals('body', (string)$command->getRequest()->getBody());
        $this->assertEquals('text/plain', $command->getRequest()->getHeader('Content-Type'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Attachments\UpdateAttachment
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\AbstractUnfuddleCommand
     */
    public function testUpdateAttachment()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.unfuddle');

        $command = new UpdateAttachment();
        $command->setProjectId(1)
                ->setId(1)
                ->setTypeId('messages', 1)
                ->setBody('body')
                ->setContentType('text/plain');
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'default');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        $message = (string)$command->getRequest();
        $this->assertContains('PUT /api/v1/projects/1/messages/1/attachments/1 HTTP/1.1', $message);
        $this->assertEquals('body', (string)$command->getRequest()->getBody());
        $this->assertEquals('text/plain', $command->getRequest()->getHeader('Content-Type'));
    }
}