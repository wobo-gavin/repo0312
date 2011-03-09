<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\ClosureLogAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\LogPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ApiCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandSet;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ConcreteCommandFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\DescriptionBuilder\XmlDescriptionBuilder;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceDescription;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command\MockCommand;

/**
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class ClientTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    protected $factory;
    protected $service;
    protected $serviceTest;
    protected $factoryTest;

    public function setUp()
    {
        $this->serviceTest = new ServiceDescription('test', 'Test service', 'http://www.test.com/', array(
            new ApiCommand(array(
                'name' => 'test_command',
                'doc' => 'documentationForCommand',
                'method' => 'DELETE',
                'can_batch' => true,
                'concrete_command_class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\Command\\MockCommand',
                'args' => array(
                    'bucket' => array(
                        'required' => true
                    ),
                    'key' => array(
                        'required' => true
                    )
                )
            ))
        ), array(
            'foo' => array(
                'default' => 'bar',
                'required' => 'true'
            ),
            'base_url' => array(
                'required' => 'true'
            ),
            'api' => array(
                'required' => 'true'
            )
        ));

        $builder = new XmlDescriptionBuilder(__DIR__ . DIRECTORY_SEPARATOR . 'test_service.xml');
        $this->service = $builder->build();

        $this->factory = new ConcreteCommandFactory($this->service);
    }

    /**
     * Get a LogPlugin
     *
     * @return LogPlugin
     */
    private function getLogPlugin()
    {
        return new LogPlugin(new ClosureLogAdapter(
            function($message, $priority, $extras = null) {
                echo $message . ' ' . $priority . ' ' . $extras . "\n";
            }
        ));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getConfig
     */
    public function testGetConfig()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(new Collection(array(
            'base_url' => 'http://www.google.com/'
        )), $this->service, $this->factory);

        $this->assertEquals('http://www.google.com/', $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('base_url'));

        $this->assertEquals(array(
            'base_url' => 'http://www.google.com/'
        ), $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::__construct
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceException
     */
    public function testConstructorValidatesConfig()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(false, $this->service, $this->factory);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::__construct
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceException
     */
    public function testConstructorValidatesBaseUrlIsProvided()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(array(), new ServiceDescription('test', 'Test service', '', array(), array()), $this->factory);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::__construct
     */
    public function testCanUseCollectionAsConfig()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(new Collection(array(
            'api' => 'v1',
            'key' => 'value',
            'base_url' => 'http://www.google.com/'
        )), $this->serviceTest, $this->factory);
        $this->assertEquals('v1', $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('api'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client
     */
    public function testInjectConfig()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(array(
            'api' => 'v1',
            'key' => 'value',
            'base_url' => 'http://www.google.com/'
        ), $this->serviceTest, $this->factory);

        $this->assertEquals('Testing...api/v1/key/value', $/* Replaced /* Replaced /* Replaced client */ */ */->injectConfig('Testing...api/{{ api }}/key/{{ key }}'));

        // Make sure that the /* Replaced /* Replaced /* Replaced client */ */ */ properly validates and injects config
        $this->assertEquals('bar', $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('foo'));

        try {
            $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(array(), $this->serviceTest, $this->factory);
            $this->fail('Did not throw exception when missing required arg');
        } catch (\Exception $e) {
            $this->assertContains('Requires that the api argument be supplied', $e->getMessage());
        }
    }

    /**
     * Test that a plugin can be attached to a /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::__construct
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getRequest
     */
    public function testClientAttachersObserversToRequests()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(array(
            'base_url' => 'http://www.google.com/'
        ), $this->service, $this->factory);

        $logPlugin = $this->getLogPlugin();

        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventManager()->attach($logPlugin);

        // Make sure the plugin was registered correctly
        $this->assertTrue($/* Replaced /* Replaced /* Replaced client */ */ */->getEventManager()->hasObserver($logPlugin));

        // Set a mock response on all requests generated by the Client
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventManager()->attach(function($subject, $event, $context) {
            if ($event == 'request.create') {
                $context->setResponse(new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response(200), true);
            }
        });

        // Get a request from the /* Replaced /* Replaced /* Replaced client */ */ */ and ensure the the observer was
        // attached to the new request
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->getRequest('GET');
        $this->assertTrue($request->getEventManager()->hasObserver($logPlugin));

        // Make sure that the log plugin actually logged the request and response
        ob_start();
        $request->send();
        $logged = ob_get_clean();
        $this->assertEquals('www.google.com - "GET / HTTP/1.1" - 200 0 - 7 /* Replaced /* Replaced /* Replaced guzzle */ */ */_request' . "\n", $logged);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getBaseUrl
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::setBaseUrl
     */
    public function testClientReturnsValidBaseUrls()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(array(
            'base_url' => 'http://www.{{ foo }}.{{ data }}/',
            'data' => '123',
            'foo' => 'bar'
        ), $this->service, $this->factory);

        $this->assertEquals('http://www.bar.123/', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl());
        $/* Replaced /* Replaced /* Replaced client */ */ */->setBaseUrl('http://www.google.com/');
        $this->assertEquals('http://www.google.com/', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::execute
     */
    public function testExecutesCommands()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n");

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(array('base_url' => $this->getServer()->getUrl()), $this->service, $this->factory);
        $cmd = new MockCommand();
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($cmd);

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Message\\Response', $cmd->getResponse());
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Message\\Response', $cmd->getResult());
        $this->assertEquals(1, count($this->getServer()->getReceivedRequests(false)));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::execute
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandSetException
     */
    public function testThrowsExceptionWhenExecutingMixedClientCommandSets()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(array('base_url' => 'http://www.test.com/'), $this->service, $this->factory);
        $otherClient = new Client(array('base_url' => 'http://www.test-123.com/'), $this->service, $this->factory);

        // Create a command set and a command
        $set = new CommandSet();
        $cmd = new MockCommand();
        $set->addCommand($cmd);

        // Associate the other /* Replaced /* Replaced /* Replaced client */ */ */ with the command
        $cmd->setClient($otherClient);

        // Send the set with the wrong /* Replaced /* Replaced /* Replaced client */ */ */, causing an exception
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($set);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::execute
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceException
     */
    public function testThrowsExceptionWhenExecutingInvalidCommandSets()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(array('base_url' => 'http://www.test.com/'), $this->service, $this->factory);
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute(new \stdClass());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::execute
     */
    public function testExecutesCommandSets()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(array('base_url' => 'http://www.test.com/'), $this->service, $this->factory);

        // Set a mock response for each request from the Client
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventManager()->attach(function($subject, $event, $context) {
            if ($event == 'request.create') {
                $context->setResponse(new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response(200), true);
            }
        });

        // Create a command set and a command
        $set = new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandSet();
        $cmd = new MockCommand();
        $set->addCommand($cmd);
        $this->assertSame($set, $/* Replaced /* Replaced /* Replaced client */ */ */->execute($set));

        // Make sure it sent
        $this->assertTrue($cmd->isExecuted());
        $this->assertTrue($cmd->isPrepared());
        $this->assertEquals(200, $cmd->getResponse()->getStatusCode());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getCommand
     */
    public function testClientUsesCommandFactory()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(
            array('base_url' => 'http://www.test.com/', 'api' => 'v1'),
            $this->serviceTest,
            new ConcreteCommandFactory($this->serviceTest)
        );

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Command\\CommandInterface', $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('test_command', array(
            'bucket' => 'test',
            'key' => 'keyTest'
        )));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getService
     */
    public function testClientUsesService()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(
            array('base_url' => 'http://www.test.com/', 'api' => 'v1'),
            $this->serviceTest,
            new ConcreteCommandFactory($this->serviceTest)
        );

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\ServiceDescription', $/* Replaced /* Replaced /* Replaced client */ */ */->getService());
        $this->assertSame($this->serviceTest, $/* Replaced /* Replaced /* Replaced client */ */ */->getService());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::setUserApplication
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getRequest
     */
    public function testSetsUserApplication()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(
            array('base_url' => 'http://www.test.com/', 'api' => 'v1'),
            $this->serviceTest,
            new ConcreteCommandFactory($this->serviceTest)
        );

        $this->assertSame($/* Replaced /* Replaced /* Replaced client */ */ */, $/* Replaced /* Replaced /* Replaced client */ */ */->setUserApplication('Test', '1.0Ab'));

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->getRequest('GET');
        $this->assertEquals('Test/1.0Ab ' . /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getDefaultUserAgent(), $request->getHeader('User-Agent'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getRequest
     */
    public function testClientAddsCurlOptionsToRequests()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(
            array(
                'base_url' => 'http://www.test.com/',
                'api' => 'v1',
                // Adds the option using the curl values
                'curl.CURLOPT_HTTPAUTH' => 'CURLAUTH_DIGEST',
                'curl.abc' => 'not added'
            ),
            $this->serviceTest,
            new ConcreteCommandFactory($this->serviceTest)
        );

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->getRequest('GET');
        $options = $request->getCurlOptions();
        $this->assertEquals(CURLAUTH_DIGEST, $options->get(CURLOPT_HTTPAUTH));
        $this->assertNull($options->get('curl.abc'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getRequest
     */
    public function testClientAddsCacheKeyFiltersToRequests()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(
            array(
                'base_url' => 'http://www.test.com/',
                'api' => 'v1',
                'cache.key_filter' => 'query=Date'
            ),
            $this->serviceTest,
            new ConcreteCommandFactory($this->serviceTest)
        );

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->getRequest('GET');
        $this->assertEquals('query=Date', $request->getParams()->get('cache.key_filter'));
    }
}