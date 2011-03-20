<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Description;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class ApiCommandTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand
     */
    public function testApiCommandIsDataObject()
    {
        $c = new ApiCommand(array(
            'name' => 'test',
            'doc' => 'doc',
            'method' => 'POST',
            'path' => '/api/v1',
            'min_args' => 2,
            'can_batch' => true,
            'args' => array(
                'key' => array(
                    'required' => 'true',
                    'type' => 'string',
                    'max_length' => 10
                ),
                'key_2' => array(
                    'required' => 'true',
                    'type' => 'integer',
                    'default' => 10
                )
           )
        ));

        $this->assertEquals('test', $c->getName());
        $this->assertEquals('doc', $c->getDoc());
        $this->assertEquals('POST', $c->getMethod());
        $this->assertEquals('/api/v1', $c->getPath());
        $this->assertEquals('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\ClosureCommand', $c->getConcreteClass());
        $this->assertEquals(2, $c->getMinArgs());
        $this->assertEquals(array(
            'key' => new Collection(array(
                'required' => 'true',
                'type' => 'string',
                'max_length' => 10
            )),
            'key_2' => new Collection(array(
                'required' => 'true',
                'type' => 'integer',
                'default' => 10
            ))
        ), $c->getArgs());

        $this->assertEquals(new Collection(array(
            'required' => 'true',
            'type' => 'integer',
            'default' => 10
        )), $c->getArg('key_2'));

        $this->assertTrue($c->canBatch());

        $this->assertEquals(array(
            'test requires at least 2 arguments',
            'Requires that the key argument be supplied.'
        ), $c->validate(new Collection(array())));

        $this->assertNull($c->getArg('afefwef'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand::__construct
     */
    public function testAllowsConcreteCommands()
    {
        $c = new ApiCommand(array(
            'name' => 'test',
            'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\ClosureCommand',
            'args' => array()
        ));
        $this->assertEquals('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\ClosureCommand', $c->getConcreteClass());
    }
}