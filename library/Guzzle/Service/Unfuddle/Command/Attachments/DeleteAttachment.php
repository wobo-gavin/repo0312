<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Attachments;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\AbstractUnfuddleCommand;

/**
 * Create an Unfuddle attachment
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ type required="true" doc="Type of attachment (messages, tickets, tickets_comments, messages_comment, notebooks)"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ type_id required="true" doc="ID of the type being deleted"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ id required="true" doc="ID of the attachment to delete"
 */
class DeleteAttachment extends AbstractAttachmentCommand
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getRequest('DELETE');
        parent::build();
    }
}