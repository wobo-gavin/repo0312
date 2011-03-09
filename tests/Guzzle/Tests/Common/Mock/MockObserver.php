<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Mock;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event\Subject;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event\EventManager;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event\Observer;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class MockObserver implements Observer
{
    public $notified = 0;
    public $subject;
    public $context;
    public $event;
    public $log = array();
    public $logByEvent = array();
    public $events = array();

   /**
     * {@inheritdoc}
     */
    public function update(Subject $subject, $event, $context = null)
    {
        $this->notified++;
        $this->subject = $subject;
        $this->context = $context;
        $this->event = $event;
        $this->events[] = $event;
        $this->log[] = array($event, $context);
        $this->logByEvent[$event] = $context;
        
        return true;
    }
}