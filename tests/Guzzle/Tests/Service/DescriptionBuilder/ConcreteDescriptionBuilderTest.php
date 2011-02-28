<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\DescriptionBuilder;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceDescription;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\DescriptionBuilder\ConcreteDescriptionBuilder;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class ConcreteDescriptionBuilderTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\DescriptionBuilder\ConcreteDescriptionBuilder
     * @expectedException ReflectionException
     */
    public function testConcreteBuilder()
    {
        $builder = new ConcreteDescriptionBuilder('Does\\Not\\Exist\\Client');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\DescriptionBuilder\ConcreteDescriptionBuilder
     */
    public function testBuildsServiceUsingClasses()
    {
        $builder = new ConcreteDescriptionBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Unfuddle\\UnfuddleClient');
        $desc = $builder->build();
        $this->assertEquals('Unfuddle', $desc->getName());
        $this->assertEquals('Client for interacting with the Unfuddle webservice', $desc->getDescription());

        // We don't care about the base url
        $this->assertEquals('', $desc->getBaseUrl());

        // Make sure that one of the known commands was created correctly
        $this->assertTrue($desc->hasCommand('tickets.create_ticket'));

        // Ensure that multiple commands were added to the service
        $this->assertTrue(count($desc->getCommands()) > 1);

        // Grab a command by name from the description
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\ApiCommand', $desc->getCommand('tickets.update_ticket'));

        // Make sure that private values are not arguments
        $this->assertArrayNotHasKey('_can_batch', $desc->getCommand('tickets.update_ticket')->getArgs());
    }
}