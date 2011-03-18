<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Builder;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\DefaultDynamicBuilder;

/**
 * @group Builder
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class DefaultDynamicBuilderTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\DefaultDynamicBuilder
     */
    public function testConstructor()
    {
        $builder = new DefaultDynamicBuilder(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'test_service.xml', array(
            'username' => 'michael',
            'password' => 'test',
            'subdomain' => 'michael'
        ));
        $this->assertEquals('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Client', $builder->getClass());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\DefaultDynamicBuilder
     */
    public function testBuildsClients()
    {
        $builder = new DefaultDynamicBuilder(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'test_service.xml', array(
            'username' => 'michael',
            'password' => 'test',
            'subdomain' => 'michael'
        ));
        
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $builder->build();
        $this->assertTrue($/* Replaced /* Replaced /* Replaced client */ */ */->getService()->hasCommand('test'));

        $command = $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('test', array(
            'bucket' => 'test',
            'key' => 'key'
        ));

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\ClosureCommand', $command);
        
        $command->prepare();

        $request = $command->getRequest();
        $this->assertEquals('DELETE', $request->getMethod());
        $this->assertEquals('www.test.com', $request->getHost());
        $this->assertEquals('/test/key.json', $request->getPath());
    }
}