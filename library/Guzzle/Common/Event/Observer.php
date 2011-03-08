<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;

/**
 * /* Replaced /* Replaced /* Replaced Guzzle */ */ */ Observer class
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */-project.org>
 */
interface Observer
{
    /**
     * Receive notifications from a EventManager
     *
     * @param Subject $subject Subject emitting the event
     * @param string $event Event signal state
     * @param mixed $context (optional) Contextual information
     *
     * @return null|bool|mixed
     */
    public function update(Subject $subject, $event, $context = null);
}