<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Attachments;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\AbstractUnfuddleCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody;

/**
 * Update an Unfuddle attachment
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ body required="true" doc="Body of the attachment"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ type required="true" doc="Type of attachment (messages, tickets, tickets_comments, messages_comment, notebooks)"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ type_id required="true" doc="ID of the type"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ id required="true" doc="ID of the attachment to download"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ content_type doc="Content type"
 */
class UpdateAttachment extends AbstractAttachmentCommand
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getRequest('PUT');
        parent::build();
        $this->request->setBody(EntityBody::factory($this->get('body')));
        $this->request->setHeader('Content-Type', $this->get('content_type'));
    }

    /**
     * Set the body of the attachment
     *
     * @param string|EntityBody|resource $body Body of the attachment
     *
     * @return UpdateAttachment
     */
    public function setBody($body)
    {
        return $this->set('body', $body);
    }

    /**
     * Set the content type of the attachment
     *
     * @param string $contentType Content-Type to set
     *
     * @return UpdateAttachment
     */
    public function setContentType($contentType)
    {
        return $this->set('content_type', $contentType);
    }
}