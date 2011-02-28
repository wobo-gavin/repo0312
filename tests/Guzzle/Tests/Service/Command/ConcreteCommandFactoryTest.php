<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ConcreteCommandFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceDescription;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ApiCommand;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class ConcreteCommandFactoryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @var ServiceDescription
     */
    protected $service;

    public function setUp()
    {
        $this->service = new ServiceDescription('test', 'Test service', 'http://www.test.com/', array(
            new ApiCommand(array(
                'name' => 'test_command',
                'doc' => 'documentationForCommand',
                'method' => 'DELETE',
                'can_batch' => true,
                'concrete_command_class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Aws\\S3\\Command\\Object\\DeleteObject',
                'args' => array(
                    'bucket' => array(
                        'required' => true
                    ),
                    'key' => array(
                        'required' => true
                    )
                )
            ))
        ));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ConcreteCommandFactory
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommandFactory
     */
    public function testConstructor()
    {
        $factory = new ConcreteCommandFactory($this->service);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommandFactory
     * @expectedException InvalidArgumentException
     */
    public function testEnsuresTheCommandExists()
    {
        $factory = new ConcreteCommandFactory($this->service);
        $factory->buildCommand('aaaa', array());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ConcreteCommandFactory
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommandFactory
     */
    public function testCreatesConcreteCommands()
    {
        $factory = new ConcreteCommandFactory($this->service);
        $command = $factory->buildCommand('test_command', array(
            'bucket' => 'test',
            'key' => 'my_key.txt'
        ));

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Aws\\S3\\Command\\Object\\DeleteObject', $command);
        $this->assertEquals('test', $command->get('bucket'));
        $this->assertEquals('my_key.txt', $command->get('key'));
    }
}