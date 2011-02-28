<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Messages;

/**
 * Update an Unfuddle message
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ id required="true" doc="Message ID"
 */
class UpdateMessage extends AbstractMessageBodyCommand
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getRequest('PUT');
        parent::build();

        $this->request->getQuery()->set('messages', $this->get('id', ''));
    }

    /**
     * Set the message ID of the command
     *
     * @param integer $id Message ID to update
     *
     * @return UpdateMessage
     */
    public function setId($id)
    {
        return $this->set('id', (int)$id);
    }
}