<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Builder;

use Doctrine\Common\Cache\ArrayCache;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\DoctrineCacheAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\DefaultBuilder;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\MockClient;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class DefaultBuilderTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\DefaultBuilder::prepareConfig
     */
    public function testPreparesConfig()
    {
        $c = DefaultBuilder::prepareConfig(array(
            'a' => '123',
            'base_url' => 'http://www.test.com/'
        ), array(
            'a' => 'xyz',
            'b' => 'lol'
        ), array('a'));

        $this->assertType('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection', $c);
        $this->assertEquals(array(
            'a' => '123',
            'b' => 'lol',
            'base_url' => 'http://www.test.com/'
        ), $c->getAll());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\DefaultBuilder::prepareConfig
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Client config must contain a 'a' key
     */
    public function testValidatesConfig()
    {
        $c = DefaultBuilder::prepareConfig(array(), array(), array('a'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\DefaultBuilder::prepareConfig
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage No base_url is set in the builder config
     */
    public function testValidatesConfigContainsBaseUrl()
    {
        $c = DefaultBuilder::prepareConfig(array());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\DefaultBuilder::build
     */
    public function testAddsFactoryAndServiceToClientAndUsesCache()
    {
        $adapter = new DoctrineCacheAdapter(new ArrayCache());
        $/* Replaced /* Replaced /* Replaced client */ */ */ = MockClient::factory(array(
            'password' => 'abc',
            'username' => '123',
            'subdomain' => 'me'
        ), $adapter);

        $this->assertType('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\MockClient', $/* Replaced /* Replaced /* Replaced client */ */ */);
        $this->assertType('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceDescription', $/* Replaced /* Replaced /* Replaced client */ */ */->getService());
        $this->assertType('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand', $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('mock_command'));

        // make sure that the adapter cached the service description
        $this->assertTrue($adapter->contains('/* Replaced /* Replaced /* Replaced guzzle */ */ */_/* Replaced /* Replaced /* Replaced guzzle */ */ */_tests_service_mock_mock/* Replaced /* Replaced /* Replaced client */ */ */'));

        // Get the service description from cache
        $/* Replaced /* Replaced /* Replaced client */ */ */ = MockClient::factory(array(
            'password' => 'abc',
            'username' => '123',
            'subdomain' => 'me'
        ), $adapter);
    }
}