<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Tickets;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\AbstractUnfuddleCommand;

/**
 * Delete an Unfuddle ticket
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ id required="true" doc="ID of the ticket to delete"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ projects required="true" doc="Project ID"
 */
class DeleteTicket extends AbstractUnfuddleCommand
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getRequest('DELETE');
        parent::build();
        $this->request->getQuery()->add('tickets', $this->get('id'));
    }

    /**
     * Set the ticket ID of the command
     *
     * @param integer $id The ticket ID
     *
     * @return DeleteTicket
     */
    public function setId($id)
    {
        return $this->set('id', (int)$id);
    }
}