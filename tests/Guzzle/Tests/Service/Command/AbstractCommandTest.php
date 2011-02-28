<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\DescriptionBuilder\XmlDescriptionBuilder;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ConcreteCommandFactory;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
abstract class AbstractCommandTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    protected function getClient()
    {
        $builder = new XmlDescriptionBuilder(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'test_service.xml');
        $service = $builder->build();
        $factory = new ConcreteCommandFactory($service);

        return new Client(array('base_url' => 'http://www.google.com/'), $service, $factory);
    }
}