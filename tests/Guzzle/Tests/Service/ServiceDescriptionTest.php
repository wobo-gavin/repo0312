<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceDescription;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\DescriptionBuilder\XmlDescriptionBuilder;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class ServiceDescriptionTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
     /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceDescription
      * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceDescription::__construct
      * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceDescription::getName
      * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceDescription::getDescription
      * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceDescription::getBaseUrl
      * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceDescription::getCommands
      * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceDescription::getCommand
     */
    public function testConstructor()
    {
        $service = new ServiceDescription('test', 'description', 'base_url', array());
        $this->assertEquals('test', $service->getName());
        $this->assertEquals('description', $service->getDescription());
        $this->assertEquals('base_url', $service->getBaseUrl());
        $this->assertEquals(array(), $service->getCommands());
        $this->assertFalse($service->hasCommand('test'));
    }
}