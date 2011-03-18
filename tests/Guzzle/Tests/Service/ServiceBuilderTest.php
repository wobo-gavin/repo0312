<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service;

use Doctrine\Common\Cache\ArrayCache;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\DoctrineCacheAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder;

/**
 * @group service
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class ServiceBuilderTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    protected $xmlConfig;
    protected $tempFile;

    public function __construct()
    {
        $this->xmlConfig = <<<EOT
<?xml version="1.0" ?>
</* Replaced /* Replaced /* Replaced guzzle */ */ */>
    </* Replaced /* Replaced /* Replaced client */ */ */s>
        </* Replaced /* Replaced /* Replaced client */ */ */ name="michael.mock" builder="/* Replaced /* Replaced /* Replaced Guzzle */ */ */.Tests.Service.Mock.MockBuilder">
            <param name="username" value="michael" />
            <param name="password" value="testing123" />
            <param name="subdomain" value="michael" />
        <//* Replaced /* Replaced /* Replaced client */ */ */>
        </* Replaced /* Replaced /* Replaced client */ */ */ name="billy.mock" builder="/* Replaced /* Replaced /* Replaced Guzzle */ */ */.Tests.Service.Mock.MockBuilder">
            <param name="username" value="billy" />
            <param name="password" value="passw0rd" />
            <param name="subdomain" value="billy" />
        <//* Replaced /* Replaced /* Replaced client */ */ */>
        </* Replaced /* Replaced /* Replaced client */ */ */ name="billy.testing" extends="billy.mock">
            <param name="subdomain" value="test.billy" />
        <//* Replaced /* Replaced /* Replaced client */ */ */>
    <//* Replaced /* Replaced /* Replaced client */ */ */s>
<//* Replaced /* Replaced /* Replaced guzzle */ */ */>
EOT;

        $this->tempFile = tempnam('/tmp', 'config.xml');
        file_put_contents($this->tempFile, $this->xmlConfig);
    }

    public function __destruct()
    {
        if (is_file($this->tempFile)) {
            unlink($this->tempFile);
        }
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::factory
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::getBuilder
     */
    public function testCanBeCreatedUsingAnXmlFile()
    {
        $builder = ServiceBuilder::factory($this->tempFile);
        $b = $builder->getBuilder('michael.mock');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Builder\\DefaultBuilder', $b);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::factory
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceException
     * @expectedExceptionMessage Unable to open service configuration file foobarfile
     */
    public function testFactoryEnsuresItCanOpenFile()
    {
        ServiceBuilder::factory('foobarfile');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::factory
     */
    public function testFactoryCanBuildServicesThatExtendOtherServices()
    {
        $s = ServiceBuilder::factory($this->tempFile);
        $s = $s->getClient('billy.testing');
        $this->assertEquals('test.billy', $s->getConfig('subdomain'));
        $this->assertEquals('billy', $s->getConfig('username'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::factory
     */
    public function testFactoryThrowsExceptionWhenBuilderExtendsNonExistentBuilder()
    {
        $xml = '<?xml version="1.0" ?>' . "\n" . '</* Replaced /* Replaced /* Replaced guzzle */ */ */></* Replaced /* Replaced /* Replaced client */ */ */s></* Replaced /* Replaced /* Replaced client */ */ */ name="invalid" extends="missing" /><//* Replaced /* Replaced /* Replaced client */ */ */s><//* Replaced /* Replaced /* Replaced guzzle */ */ */>';
        $tempFile = tempnam('/tmp', 'config.xml');
        file_put_contents($tempFile, $xml);

        try {
            ServiceBuilder::factory($tempFile);
            unlink($tempFile);
            $this->fail('Test did not throw ServiceException');
        } catch (\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceException $e) {
            $this->assertEquals('invalid is trying to extend a non-existent or not yet defined service: missing', $e->getMessage());
        }

        unlink($tempFile);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::factory
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::setCache
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder
     */
    public function testFactoryUsesCacheAdapterWhenAvailable()
    {
        $cache = new ArrayCache();
        $adapter = new DoctrineCacheAdapter($cache);
        $this->assertEmpty($cache->getIds());

        $s1 = ServiceBuilder::factory($this->tempFile, $adapter, 86400);

        // Make sure it added to the cache
        $this->assertNotEmpty($cache->getIds());

        // Load this one from cache
        $s2 = ServiceBuilder::factory($this->tempFile, $adapter, 86400);

        $builder = ServiceBuilder::factory($this->tempFile);
        $this->assertEquals($s1, $s2);

        $this->assertSame($s1, $s1->setCache($adapter, 86400));
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $s1->getClient('michael.mock');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::getBuilder
     */
    public function testBuildersAreStoredForPerformance()
    {
        $builder = ServiceBuilder::factory($this->tempFile);
        $b = $builder->getBuilder('michael.mock');
        $this->assertTrue($b === $builder->getBuilder('michael.mock'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::getBuilder
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceException
     * @expectedExceptionMessage No service builder is registered as foobar
     */
    public function testThrowsExceptionWhenGettingInvalidBuilder()
    {
        ServiceBuilder::factory($this->tempFile)->getBuilder('foobar');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::getClient
     */
    public function testGetClientStoresClientCopy()
    {
        $builder = ServiceBuilder::factory($this->tempFile);
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $builder->getClient('michael.mock');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient', $/* Replaced /* Replaced /* Replaced client */ */ */);
        $this->assertEquals('http://127.0.0.1:8124/v1/michael', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl());
        $this->assertEquals($/* Replaced /* Replaced /* Replaced client */ */ */, $builder->getClient('michael.mock'));

        // Get another /* Replaced /* Replaced /* Replaced client */ */ */ but throw this one away
        $/* Replaced /* Replaced /* Replaced client */ */ */2 = $builder->getClient('billy.mock', true);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient', $/* Replaced /* Replaced /* Replaced client */ */ */2);
        $this->assertEquals('http://127.0.0.1:8124/v1/billy', $/* Replaced /* Replaced /* Replaced client */ */ */2->getBaseUrl());

        // Make sure the original /* Replaced /* Replaced /* Replaced client */ */ */ is still there and set
        $this->assertTrue($/* Replaced /* Replaced /* Replaced client */ */ */ === $builder->getClient('michael.mock'));

        // Create a new billy.mock /* Replaced /* Replaced /* Replaced client */ */ */ that is stored
        $/* Replaced /* Replaced /* Replaced client */ */ */3 = $builder->getClient('billy.mock');

        // Make sure that the stored billy.mock /* Replaced /* Replaced /* Replaced client */ */ */ is equal to the other stored /* Replaced /* Replaced /* Replaced client */ */ */
        $this->assertTrue($/* Replaced /* Replaced /* Replaced client */ */ */3 === $builder->getClient('billy.mock'));

        // Make sure that this /* Replaced /* Replaced /* Replaced client */ */ */ is not equal to the previous throwaway /* Replaced /* Replaced /* Replaced client */ */ */
        $this->assertFalse($/* Replaced /* Replaced /* Replaced client */ */ */2 === $builder->getClient('billy.mock'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::getBuilder
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceException
     * @expectedExceptionMessage A class attribute must be present when using /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\DefaultBuilder
     */
    public function testThrowsExceptionWhenGettingDefaultBuilderWithNoClassSpecified()
    {
        $s = new ServiceBuilder(array(
            'michael.mock' => array(
                'builder' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */.Service.Builder.DefaultBuilder',
                'params' => array(
                    'base_url' => 'http://www.test.com/',
                    'username' => 'michael'
                )
            )
        ));

        $s->getBuilder('michael.mock');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder
     */
    public function testBuildersPassOptionsThroughToClients()
    {
        $s = new ServiceBuilder(array(
            'michael.mock' => array(
                'builder' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */.Service.Builder.DefaultBuilder',
                'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient',
                'params' => array(
                    'base_url' => 'http://www.test.com/',
                    'subdomain' => 'michael',
                    'password' => 'test',
                    'username' => 'michael',
                    'curl.curlopt_proxyport' => 8080
                )
            )
        ));

        $c = $s->getBuilder('michael.mock')->build();
        $this->assertEquals(8080, $c->getConfig('curl.curlopt_proxyport'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder
     */
    public function testUsesTheDefaultBuilderWhenNoBuilderIsSpecified()
    {
        $s = new ServiceBuilder(array(
            'michael.mock' => array(
                'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient',
                'params' => array(
                    'base_url' => 'http://www.test.com/',
                    'subdomain' => 'michael',
                    'password' => 'test',
                    'username' => 'michael',
                    'curl.curlopt_proxyport' => 8080
                )
            )
        ));

        $c = $s->getBuilder('michael.mock')->build();
        $this->assertType('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient', $c);
    }
}