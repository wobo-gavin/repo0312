<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common;

use \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Subject\SubjectMediator;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class AbstractSubjectTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Subject\AbstractSubject::getSubjectMediator
     */
    public function testGetSubjectMediator()
    {
        $subject = new Mock\MockSubject();
        $mediator = $subject->getSubjectMediator();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Subject\SubjectMediator', $mediator);
        $this->assertEquals($mediator, $subject->getSubjectMediator());
    }
}