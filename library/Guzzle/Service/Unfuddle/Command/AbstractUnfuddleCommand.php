<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand;

/**
 * Base unfuddle command class
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
abstract class AbstractUnfuddleCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        if ($this->hasKey('projects')) {
            $this->request->getQuery()->set('projects', $this->get('projects'));
        }
        
        // Unfuddle requires that the content-type be set as application/xml
        $this->request->setHeader('Content-Type', 'application/xml');
    }

    /**
     * Set the project ID of the command
     *
     * @param integer $id Project ID
     *
     * @return AbstractUnfuddleCommand
     */
    public function setProjectId($id)
    {
        return $this->set('projects', (int)$id);
    }
}