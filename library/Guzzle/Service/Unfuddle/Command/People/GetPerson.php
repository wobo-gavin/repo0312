<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\People;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\AbstractUnfuddleCommand;

/**
 * Get a person
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ id required="true" doc="ID of the person to retrieve"
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class GetPerson extends AbstractUnfuddleCommand
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getRequest('GET');
        $this->request->getQuery()->add('people', $this->getId());
    }

    /**
     * Set the ID of the person
     *
     * @param integer $id ID of the person to retrieve
     *
     * @return GetPerson
     */
    public function setId($id)
    {
        return $this->set('id', $id);
    }

    /**
     * Get the ID of the person
     *
     * @return integer
     */
    public function getId()
    {
        return $this->get('id');
    }
}