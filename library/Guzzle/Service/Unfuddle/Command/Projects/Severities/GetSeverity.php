<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\Projects\Severities;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\AbstractUnfuddleCommand;

/**
 * Get the severities associated with a ticket
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class GetSeverity extends AbstractUnfuddleCommand
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getRequest('GET');
        parent::build();
        if ($this->hasKey('id')) {
            $this->request->getQuery()->add('severities', $this->get('id', ''));
        } else {
            $this->request->getQuery()->add('severities', false);
        }
    }

    /**
     * Set the severity ID of the command
     *
     * @param integer $id The severity ID to retrieve
     *
     * @return GetSeverity
     */
    public function setSeverityId($id)
    {
        return $this->set('id', (int)$id);
    }
}