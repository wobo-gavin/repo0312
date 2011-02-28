<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Tickets;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\AbstractUnfuddleCommand;

/**
 * Get an Unfuddle ticket
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ projects doc="Project ID"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ by_number doc="Ticket to GET by number"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ id doc="Ticket to GET by ID"
 */
class GetTicket extends AbstractUnfuddleCommand
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {   
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getRequest('GET');
        parent::build();
        if ($this->hasKey('id')) {
            $this->request->getQuery()->add('tickets', $this->get('id'));
        } else if ($this->hasKey('by_number')) {            
            $this->request->getQuery()->add('tickets', false);
            $this->request->getQuery()->add('by_number', $this->get('by_number'));
        } else {
            $this->request->getQuery()->add('tickets', false);
        }
    }

    /**
     * Set the ticket ID of the command
     *
     * @param integer $id The ticket ID to retrieve
     *
     * @return GetTicket
     */
    public function setId($id)
    {
        return $this->set('id', (int)$id);
    }

    /**
     * Set the ticket number of the command
     *
     * @param integer $number The ticket number to retrieve
     *
     * @return GetTicket
     */
    public function setTicketNumber($id)
    {
        return $this->set('by_number', (int)$id);
    }
}