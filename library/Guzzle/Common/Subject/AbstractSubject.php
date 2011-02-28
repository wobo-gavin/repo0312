<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Subject;

/**
 * Abstract subject class
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */-project.org>
 */
abstract class AbstractSubject implements Subject
{
    /**
     * @var SubjectMediator
     */
    protected $subjectMediator;

    /**
     * Get the subject mediator associated with the subject
     *
     * @return SubjectMediator
     */
    public function getSubjectMediator()
    {
        if (!$this->subjectMediator) {
            $this->subjectMediator = new SubjectMediator($this);
        }

        return $this->subjectMediator;
    }
}