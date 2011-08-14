<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service;

use Doctrine\Common\Cache\ArrayCache;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\DoctrineCacheAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class ServiceBuilderTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    protected $xmlConfig;
    protected $tempFile;

    protected $arrayData = array(
        'michael.mock' => array(
            'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient',
            'params' => array(
                'username' => 'michael',
                'password' => 'testing123',
                'subdomain' => 'michael',
            ),
        ),
        'billy.mock' => array(
            'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient',
            'params' => array(
                'username' => 'billy',
                'password' => 'passw0rd',
                'subdomain' => 'billy',
            ),
        ),
        'billy.testing' => array(
            'extends' => 'billy.mock',
            'params' => array(
                'subdomain' => 'test.billy',
            ),
        ),
    );

    public function __construct()
    {
        $this->xmlConfig = <<<EOT
<?xml version="1.0" ?>
</* Replaced /* Replaced /* Replaced guzzle */ */ */>
    </* Replaced /* Replaced /* Replaced client */ */ */s>
        </* Replaced /* Replaced /* Replaced client */ */ */ name="michael.mock" class="/* Replaced /* Replaced /* Replaced Guzzle */ */ */.Tests.Service.Mock.MockClient">
            <param name="username" value="michael" />
            <param name="password" value="testing123" />
            <param name="subdomain" value="michael" />
        <//* Replaced /* Replaced /* Replaced client */ */ */>
        </* Replaced /* Replaced /* Replaced client */ */ */ name="billy.mock" class="/* Replaced /* Replaced /* Replaced Guzzle */ */ */.Tests.Service.Mock.MockClient">
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
     */
    public function testCanBeCreatedUsingAnXmlFile()
    {
        $builder = ServiceBuilder::factory($this->tempFile, null, 86400, 'xml');
        $c = $builder->get('michael.mock');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient', $c);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::factory
     * @expectedException RuntimeException
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
        $s = ServiceBuilder::factory($this->tempFile, null, 86400, 'xml');
        $s = $s->get('billy.testing');
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
            ServiceBuilder::factory($tempFile, null, 86400, 'xml');
            unlink($tempFile);
            $this->fail('Test did not throw ServiceException');
        } catch (\LogicException $e) {
            $this->assertEquals('invalid is trying to extend a non-existent service: missing', $e->getMessage());
        }

        unlink($tempFile);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::factory
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder
     */
    public function testFactoryUsesCacheAdapterWhenAvailable()
    {
        $cache = new ArrayCache();
        $adapter = new DoctrineCacheAdapter($cache);
        $this->assertEmpty($cache->getIds());

        $s1 = ServiceBuilder::factory($this->tempFile, $adapter, 86400, 'xml');

        // Make sure it added to the cache with a proper cache key
        $keys = $cache->getIds();
        $this->assertNotEmpty($keys);
        $this->assertEquals(0, strpos($keys[0], 'guz_'));
        $this->assertFalse(strpos($keys[0], '__'));

        // Load this one from cache
        $s2 = ServiceBuilder::factory($this->tempFile, $adapter, 86400, 'xml');

        $builder = ServiceBuilder::factory($this->tempFile, null, 86400, 'xml');
        $this->assertEquals($s1, $s2);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::get
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage No /* Replaced /* Replaced /* Replaced client */ */ */ is registered as foobar
     */
    public function testThrowsExceptionWhenGettingInvalidClient()
    {
        ServiceBuilder::factory($this->tempFile, null, 86400, 'xml')->get('foobar');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::get
     */
    public function testStoresClientCopy()
    {
        $builder = ServiceBuilder::factory($this->tempFile, null, 86400, 'xml');
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $builder->get('michael.mock');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient', $/* Replaced /* Replaced /* Replaced client */ */ */);
        $this->assertEquals('http://127.0.0.1:8124/v1/michael', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl());
        $this->assertEquals($/* Replaced /* Replaced /* Replaced client */ */ */, $builder->get('michael.mock'));

        // Get another /* Replaced /* Replaced /* Replaced client */ */ */ but throw this one away
        $/* Replaced /* Replaced /* Replaced client */ */ */2 = $builder->get('billy.mock', true);
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient', $/* Replaced /* Replaced /* Replaced client */ */ */2);
        $this->assertEquals('http://127.0.0.1:8124/v1/billy', $/* Replaced /* Replaced /* Replaced client */ */ */2->getBaseUrl());

        // Make sure the original /* Replaced /* Replaced /* Replaced client */ */ */ is still there and set
        $this->assertTrue($/* Replaced /* Replaced /* Replaced client */ */ */ === $builder->get('michael.mock'));

        // Create a new billy.mock /* Replaced /* Replaced /* Replaced client */ */ */ that is stored
        $/* Replaced /* Replaced /* Replaced client */ */ */3 = $builder->get('billy.mock');

        // Make sure that the stored billy.mock /* Replaced /* Replaced /* Replaced client */ */ */ is equal to the other stored /* Replaced /* Replaced /* Replaced client */ */ */
        $this->assertTrue($/* Replaced /* Replaced /* Replaced client */ */ */3 === $builder->get('billy.mock'));

        // Make sure that this /* Replaced /* Replaced /* Replaced client */ */ */ is not equal to the previous throwaway /* Replaced /* Replaced /* Replaced client */ */ */
        $this->assertFalse($/* Replaced /* Replaced /* Replaced client */ */ */2 === $builder->get('billy.mock'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder
     */
    public function testBuildersPassOptionsThroughToClients()
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

        $c = $s->get('michael.mock');
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

        $c = $s->get('michael.mock');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\MockClient', $c);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::offsetSet
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::offsetGet
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::offsetUnset
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::offsetExists
     */
    public function testUsedAsArray()
    {
        $b = ServiceBuilder::factory($this->tempFile, null, 86400, 'xml');
        $this->assertTrue($b->offsetExists('michael.mock'));
        $this->assertFalse($b->offsetExists('not_there'));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Client', $b['michael.mock']);

        unset($b['michael.mock']);
        $this->assertFalse($b->offsetExists('michael.mock'));

        $b['michael.mock'] = new Client('http://www.test.com/');
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Client', $b['michael.mock']);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::factory
     */
    public function testFactoryCanCreateFromXml()
    {
        $b = ServiceBuilder::factory(new \SimpleXMLElement($this->xmlConfig));
        $this->assertTrue($b->offsetExists('michael.mock'));
        $this->assertTrue($b->offsetExists('billy.mock'));
        $this->assertTrue($b->offsetExists('billy.testing'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::factory
     */
    public function testFactoryCanCreateFromJson()
    {
        $tmp = sys_get_temp_dir() . 'test.js';
        file_put_contents($tmp, json_encode($this->arrayData));
        $b = ServiceBuilder::factory($tmp);
        unlink($tmp);
        $s = $b->get('billy.testing');
        $this->assertEquals('test.billy', $s->getConfig('subdomain'));
        $this->assertEquals('billy', $s->getConfig('username'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::factory
     */
    public function testFactoryCanCreateFromArray()
    {
        $b = ServiceBuilder::factory($this->arrayData);
        $s = $b->get('billy.testing');
        $this->assertEquals('test.billy', $s->getConfig('subdomain'));
        $this->assertEquals('billy', $s->getConfig('username'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::factory
     * @expectedException RuntimeException
     * @expectedExceptionMessage Unknown file type abc
     */
    public function testFactoryValidatesFileExtension()
    {
        $tmp = sys_get_temp_dir() . 'test.abc';
        file_put_contents($tmp, 'data');
        try {
            ServiceBuilder::factory($tmp);
        } catch (\RuntimeException $e) {
            unlink($tmp);
            throw $e;
        }
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder::factory
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage $data must be an instance of SimpleXMLElement
     */
    public function testFactoryValidatesObjectTypes()
    {
        ServiceBuilder::factory(new \stdClass());
    }
}