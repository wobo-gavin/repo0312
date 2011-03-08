<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;

/**
 * Abstract subject class
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */-project.org>
 */
abstract class AbstractSubject implements Subject
{
    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * Get the subject mediator associated with the subject
     *
     * @return EventManager
     */
    public function getEventManager()
    {
        if (!$this->eventManager) {
            $this->eventManager = new EventManager($this);
        }

        return $this->eventManager;
    }
}