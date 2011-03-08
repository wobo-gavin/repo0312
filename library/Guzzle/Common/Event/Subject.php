<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;

/**
 * /* Replaced /* Replaced /* Replaced Guzzle */ */ */ subject interface
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */-project.org>
 */
interface Subject
{
    /**
     * STATE_UNCHANGED is used when dispatching events so that the state
     * will remain unchanged from the previous state.
     *
     * @var string
     */
    const STATE_UNCHANGED = 'unchaged';

    /**
     * Get the subject mediator associated with the subject
     *
     * @return EventManager
     */
    public function getEventManager();
}