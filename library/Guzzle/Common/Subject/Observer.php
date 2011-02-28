<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Subject;

/**
 * /* Replaced /* Replaced /* Replaced Guzzle */ */ */ Observer class
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */-project.org>
 */
interface Observer
{
    /**
     * Receive notifications from a SubjectMediator
     *
     * @param SubjectMediator $subject Subject mediator sending the update
     */
    public function update(SubjectMediator $subject);
}