<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Unfuddle\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\People\GetCurrentPerson;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\People\GetPeople;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\People\GetPerson;

/**
 * @group Unfuddle
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class PeopleTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\People\GetCurrentPerson
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\AbstractUnfuddleCommand
     */
    public function testCommandGetsCurrentPerson()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.unfuddle');
        $command = new GetCurrentPerson();
        $this->assertSame($command, $command->setProjectId(1));
        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'people.get_person');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $this->assertContains('/api/v1/people/current', $command->getRequest()->getUrl());
        $this->assertEquals('test@test.com', (string)$command->getResult()->email);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\People\GetPeople
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\AbstractUnfuddleCommand
     */
    public function testCommandGetsPeople()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.unfuddle');
        $command = new GetPeople(array(
            'projects' => 1
        ));

        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'people.get_people');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);
        
        $this->assertContains('/api/v1/projects/1/people', $command->getRequest()->getUrl());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Unfuddle\Command\People\GetPerson
     */
    public function testCommandGetsSpecificPerson()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getServiceBuilder()->getClient('test.unfuddle');
        $command = new GetPerson();

        $this->assertSame($command, $command->setId('1'));

        $this->setMockResponse($/* Replaced /* Replaced /* Replaced client */ */ */, 'people.get_person');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($command);

        $this->assertContains('/api/v1/people/1', $command->getRequest()->getUrl());
    }
}