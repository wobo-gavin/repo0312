<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\ClosureLogAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\LogPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandSet;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\XmlDescriptionBuilder;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription;
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
        $this->serviceTest = new ServiceDescription(array(
            new ApiCommand(array(
                'name' => 'test_command',
                'doc' => 'documentationForCommand',
                'method' => 'DELETE',
                'can_batch' => true,
                'class' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\Command\\MockCommand',
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

        $builder = new XmlDescriptionBuilder(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'TestData' . DIRECTORY_SEPARATOR . 'test_service.xml');
        $this->service = $builder->build();
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
                echo $message . ' ' . $priority . ' ' . implode(' - ', (array) $extras) . "\n";
            }
        ));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getConfig
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::setConfig
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::setBaseUrl
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getBaseUrl
     */
    public function testAcceptsConfig()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.google.com/');
        $this->assertEquals('http://www.google.com/', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl());
        $this->assertSame($/* Replaced /* Replaced /* Replaced client */ */ */, $/* Replaced /* Replaced /* Replaced client */ */ */->setConfig(array(
            'test' => '123'
        )));
        $this->assertEquals(array('test' => '123'), $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig()->getAll());
        $this->assertEquals('123', $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('test'));
        $this->assertSame($/* Replaced /* Replaced /* Replaced client */ */ */, $/* Replaced /* Replaced /* Replaced client */ */ */->setBaseUrl('http://www.test.com/{{test}}'));
        $this->assertEquals('http://www.test.com/123', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl());
        $this->assertEquals('http://www.test.com/{{test}}', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl(false));

        try {
            $/* Replaced /* Replaced /* Replaced client */ */ */->setConfig(false);
        } catch (\InvalidArgumentException $e) {
        }
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::__construct
     */
    public function testConstructorCanAcceptConfig()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.test.com/', array(
            'data' => '123'
        ));
        $this->assertEquals('123', $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('data'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::setConfig
     */
    public function testCanUseCollectionAsConfig()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.google.com/');
        $/* Replaced /* Replaced /* Replaced client */ */ */->setConfig(new Collection(array(
            'api' => 'v1',
            'key' => 'value',
            'base_url' => 'http://www.google.com/'
        )));
        $this->assertEquals('v1', $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('api'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::factory
     */
    public function testFactoryCreatesClient()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = Client::factory(array(
            'base_url' => 'http://www.test.com/',
            'test' => '123'
        ));

        $this->assertEquals('http://www.test.com/', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl());
        $this->assertEquals('123', $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('test'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client
     */
    public function testInjectConfig()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.google.com/');
        $/* Replaced /* Replaced /* Replaced client */ */ */->setConfig(array(
            'api' => 'v1',
            'key' => 'value',
            'foo' => 'bar'
        ));
        $this->assertEquals('Testing...api/v1/key/value', $/* Replaced /* Replaced /* Replaced client */ */ */->inject('Testing...api/{{api}}/key/{{key}}'));

        // Make sure that the /* Replaced /* Replaced /* Replaced client */ */ */ properly validates and injects config
        $this->assertEquals('bar', $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('foo'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::__construct
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::createRequest
     */
    public function testClientAttachersObserversToRequests()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n");

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $logPlugin = $this->getLogPlugin();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventManager()->attach($logPlugin);

        // Make sure the plugin was registered correctly
        $this->assertTrue($/* Replaced /* Replaced /* Replaced client */ */ */->getEventManager()->hasObserver($logPlugin));

        // Get a request from the /* Replaced /* Replaced /* Replaced client */ */ */ and ensure the the observer was
        // attached to the new request
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest();
        $this->assertTrue($request->getEventManager()->hasObserver($logPlugin));

        // Make sure that the log plugin actually logged the request and response
        ob_start();
        $request->send();
        $logged = ob_get_clean();
        $this->assertContains('"GET / HTTP/1.1" - 200', $logged);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getBaseUrl
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::setBaseUrl
     */
    public function testClientReturnsValidBaseUrls()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.{{foo}}.{{data}}/', array(
            'data' => '123',
            'foo' => 'bar'
        ));
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

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
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
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.test.com/');
        $otherClient = new Client('http://www.test-123.com/');

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
     * @expectedException InvalidArgumentException
     */
    public function testThrowsExceptionWhenExecutingInvalidCommandSets()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.test.com/');
        $/* Replaced /* Replaced /* Replaced client */ */ */->execute(new \stdClass());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::execute
     */
    public function testExecutesCommandSets()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.test.com/');

        // Set a mock response for each request from the Client
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventManager()->attach(function($subject, $event, $context) {
            if ($event == 'request.create') {
                $context->setResponse(new \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response(200), true);
            }
        });

        // Create a command set and a command
        $set = new CommandSet();
        $cmd = new MockCommand();
        $set->addCommand($cmd);
        $this->assertSame($set, $/* Replaced /* Replaced /* Replaced client */ */ */->execute($set));

        // Make sure it sent
        $this->assertTrue($cmd->isExecuted());
        $this->assertTrue($cmd->isPrepared());
        $this->assertEquals(200, $cmd->getResponse()->getStatusCode());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::setUserApplication
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::createRequest
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::prepareRequest
     */
    public function testSetsUserApplication()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.test.com/', array(
            'api' => 'v1'
        ));

        $this->assertSame($/* Replaced /* Replaced /* Replaced client */ */ */, $/* Replaced /* Replaced /* Replaced client */ */ */->setUserApplication('Test', '1.0Ab'));
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $this->assertEquals('Test/1.0Ab ' . /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getDefaultUserAgent(), $request->getHeader('User-Agent'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::createRequest
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::prepareRequest
     */
    public function testClientAddsCurlOptionsToRequests()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.test.com/', array(
            'api' => 'v1',
            // Adds the option using the curl values
            'curl.CURLOPT_HTTPAUTH' => 'CURLAUTH_DIGEST',
            'curl.abc' => 'not added'
        ));

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest();
        $options = $request->getCurlOptions();
        $this->assertEquals(CURLAUTH_DIGEST, $options->get(CURLOPT_HTTPAUTH));
        $this->assertNull($options->get('curl.abc'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::createRequest
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::prepareRequest
     */
    public function testClientAddsCacheKeyFiltersToRequests()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.test.com/', array(
            'api' => 'v1',
            'cache.key_filter' => 'query=Date'
        ));

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest();
        $this->assertEquals('query=Date', $request->getParams()->get('cache.key_filter'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getCommand
     * @expectedException InvalidArgumentException
     */
    public function testThrowsExceptionWhenNoCommandFactoryIsSetAndGettingCommand()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('test');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getCommand
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getDescription
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::setDescription
     */
    public function testRetrievesCommandsFromConcreteAndService()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Mock\MockClient('http://www.example.com/');
        $this->assertSame($/* Replaced /* Replaced /* Replaced client */ */ */, $/* Replaced /* Replaced /* Replaced client */ */ */->setDescription($this->serviceTest));
        $this->assertSame($this->serviceTest, $/* Replaced /* Replaced /* Replaced client */ */ */->getDescription());
        // Creates service commands
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\Command\\MockCommand', $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('test_command'));
        // Creates concrete commands
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Tests\\Service\\Mock\\Command\\OtherCommand', $/* Replaced /* Replaced /* Replaced client */ */ */->getCommand('other_command'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::prepareRequest
     */
    public function testPreparesRequestsNotCreatedByTheClient()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventManager()->attach(new ExponentialBackoffPlugin());
        $request = RequestFactory::get($/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl());
        $this->assertSame($request, $/* Replaced /* Replaced /* Replaced client */ */ */->prepareRequest($request));
        $this->assertTrue($request->getEventManager()->hasObserver('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Plugin\\ExponentialBackoffPlugin'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::createRequest
     */
    public function testCreatesRequestsWithDefaultValues()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl() . 'base');

        // Create a GET request
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals($/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl(), $request->getUrl());

        // Create a DELETE request
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('DELETE');
        $this->assertEquals('DELETE', $request->getMethod());
        $this->assertEquals($/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl(), $request->getUrl());

        // Create a HEAD request with custom headers
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('HEAD', 'http://www.test.com/');
        $this->assertEquals('HEAD', $request->getMethod());
        $this->assertEquals('http://www.test.com/', $request->getUrl());

        // Create a PUT request
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('PUT');
        $this->assertEquals('PUT', $request->getMethod());

        // Create a PUT request with injected config
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('PUT', '/path/{{a}}?q={{b}}', array(
            'a' => '1',
            'b' => '2'
        ));
        $this->assertEquals($request->getUrl(), $this->getServer()->getUrl() . 'path/1?q=2');

        // Realtive URL with relative path
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'relative/path/to/resource');
        $this->assertEquals($this->getServer()->getUrl() . 'base/relative/path/to/resource', $request->getUrl());

        // Realtive URL with relative path and query
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'relative/path/to/resource?a=b&c=d');
        $this->assertEquals($this->getServer()->getUrl() . 'base/relative/path/to/resource?a=b&c=d', $request->getUrl());

        // Relative URL with absolute path
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/absolute/path/to/resource');
        $this->assertEquals($this->getServer()->getUrl() . 'absolute/path/to/resource', $request->getUrl());

        // Relative URL with absolute path and query
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/absolute/path/to/resource?a=b&c=d');
        $this->assertEquals($this->getServer()->getUrl() . 'absolute/path/to/resource?a=b&c=d', $request->getUrl());

        // Test with a base URL containing a query string too
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl() . 'base?z=1');

        // Absolute so replaces query
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '/absolute/path/to/resource?a=b&c=d');
        $this->assertEquals($this->getServer()->getUrl() . 'absolute/path/to/resource?a=b&c=d', $request->getUrl());

        // Add relative with no query
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'relative/path/to/resource');
        $this->assertEquals($this->getServer()->getUrl() . 'base/relative/path/to/resource?z=1', $request->getUrl());

        // Add relative with query
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'relative/path/to/resource?another=query');
        $this->assertEquals($this->getServer()->getUrl() . 'base/relative/path/to/resource?z=1&another=query', $request->getUrl());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::get
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::delete
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::head
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::put
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::post
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::options
     */
    public function testClientHasHelperMethodsForCreatingRequests()
    {
        $url = $this->getServer()->getUrl();
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($url . 'base');
        $this->assertEquals('GET', $/* Replaced /* Replaced /* Replaced client */ */ */->get()->getMethod());
        $this->assertEquals('PUT', $/* Replaced /* Replaced /* Replaced client */ */ */->put()->getMethod());
        $this->assertEquals('POST', $/* Replaced /* Replaced /* Replaced client */ */ */->post()->getMethod());
        $this->assertEquals('HEAD', $/* Replaced /* Replaced /* Replaced client */ */ */->head()->getMethod());
        $this->assertEquals('DELETE', $/* Replaced /* Replaced /* Replaced client */ */ */->delete()->getMethod());
        $this->assertEquals('OPTIONS', $/* Replaced /* Replaced /* Replaced client */ */ */->options()->getMethod());
        $this->assertEquals($url . 'base/abc', $/* Replaced /* Replaced /* Replaced client */ */ */->get('abc')->getUrl());
        $this->assertEquals($url . 'zxy', $/* Replaced /* Replaced /* Replaced client */ */ */->put('/zxy')->getUrl());
        $this->assertEquals($url . 'zxy?a=b', $/* Replaced /* Replaced /* Replaced client */ */ */->post('/zxy?a=b')->getUrl());
        $this->assertEquals($url . 'base?a=b', $/* Replaced /* Replaced /* Replaced client */ */ */->head('?a=b')->getUrl());
        $this->assertEquals($url . 'base?a=b', $/* Replaced /* Replaced /* Replaced client */ */ */->delete('/base?a=b')->getUrl());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client::getBaseUrl
     * @expectedException RuntimeException
     */
    public function testClientEnsuresBaseUrlIsSetWhenRetrievingIt()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('');
        $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl();
    }
}