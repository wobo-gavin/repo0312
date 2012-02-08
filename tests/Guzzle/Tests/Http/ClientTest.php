<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\ClosureLogAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\UriTemplate;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\ExponentialBackoffPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\LogPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;

/**
 * @group server
 */
class ClientTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
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
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::getConfig
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::setConfig
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::setBaseUrl
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::getBaseUrl
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
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::getAllEvents
     */
    public function testDescribesEvents()
    {
        $this->assertEquals(array('/* Replaced /* Replaced /* Replaced client */ */ */.create_request'), Client::getAllEvents());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::__toString
     */
    public function testConvertsToString()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $hash = spl_object_hash($/* Replaced /* Replaced /* Replaced client */ */ */);
        $this->assertEquals($hash, (string) $/* Replaced /* Replaced /* Replaced client */ */ */);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::__construct
     */
    public function testConstructorCanAcceptConfig()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.test.com/', array(
            'data' => '123'
        ));
        $this->assertEquals('123', $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('data'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::setConfig
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
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client
     */
    public function testExpandsUriTemplatesUsingConfig()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.google.com/');
        $/* Replaced /* Replaced /* Replaced client */ */ */->setConfig(array(
            'api' => 'v1',
            'key' => 'value',
            'foo' => 'bar'
        ));
        $this->assertEquals('Testing...api/v1/key/value', $/* Replaced /* Replaced /* Replaced client */ */ */->expandTemplate('Testing...api/{api}/key/{{key}}'));

        // Make sure that the /* Replaced /* Replaced /* Replaced client */ */ */ properly validates and injects config
        $this->assertEquals('bar', $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('foo'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::__construct
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::createRequest
     */
    public function testClientAttachersObserversToRequests()
    {
        $this->getServer()->flush();
        $this->getServer()->enqueue("HTTP/1.1 200 OK\r\nContent-Length: 0\r\n\r\n");

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $logPlugin = $this->getLogPlugin();
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($logPlugin);

        // Get a request from the /* Replaced /* Replaced /* Replaced client */ */ */ and ensure the the observer was
        // attached to the new request
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest();
        $this->assertTrue($this->hasSubscriber($request, $logPlugin));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::getBaseUrl
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::setBaseUrl
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
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::setUserAgent
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::createRequest
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::prepareRequest
     */
    public function testSetsUserAgent()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.test.com/', array(
            'api' => 'v1'
        ));

        $this->assertSame($/* Replaced /* Replaced /* Replaced client */ */ */, $/* Replaced /* Replaced /* Replaced client */ */ */->setUserAgent('Test/1.0Ab', true));
        $this->assertEquals('Test/1.0Ab ' . /* Replaced /* Replaced /* Replaced Guzzle */ */ */::getDefaultUserAgent(), $/* Replaced /* Replaced /* Replaced client */ */ */->get()->getHeader('User-Agent'));
        $/* Replaced /* Replaced /* Replaced client */ */ */->setUserAgent('Test/1.0Ab');
        $this->assertEquals('Test/1.0Ab', $/* Replaced /* Replaced /* Replaced client */ */ */->get()->getHeader('User-Agent'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::createRequest
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::prepareRequest
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
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::createRequest
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::prepareRequest
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
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::prepareRequest
     */
    public function testPreparesRequestsNotCreatedByTheClient()
    {
        $exp = new ExponentialBackoffPlugin();
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($exp);
        $request = RequestFactory::create('GET', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl());
        $this->assertSame($request, $/* Replaced /* Replaced /* Replaced client */ */ */->prepareRequest($request));
        $this->assertTrue($this->hasSubscriber($request, $exp));
    }

    public function urlProvider()
    {
        $u = $this->getServer()->getUrl() . 'base/';
        $u2 = $this->getServer()->getUrl() . 'base?z=1';
        return array(
            array($u, '', $u),
            array($u, 'relative/path/to/resource', $u . 'relative/path/to/resource'),
            array($u, 'relative/path/to/resource?a=b&c=d', $u . 'relative/path/to/resource?a=b&c=d'),
            array($u, '/absolute/path/to/resource', $this->getServer()->getUrl() . 'absolute/path/to/resource'),
            array($u, '/absolute/path/to/resource?a=b&c=d', $this->getServer()->getUrl() . 'absolute/path/to/resource?a=b&c=d'),
            array($u2, '/absolute/path/to/resource?a=b&c=d', $this->getServer()->getUrl()  . 'absolute/path/to/resource?a=b&c=d'),
            array($u2, 'relative/path/to/resource', $this->getServer()->getUrl() . 'base/relative/path/to/resource?z=1'),
            array($u2, 'relative/path/to/resource?another=query', $this->getServer()->getUrl() . 'base/relative/path/to/resource?z=1&another=query')
        );
    }

    /**
     * @dataProvider urlProvider
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::createRequest
     */
    public function testBuildsRelativeUrls($baseUrl, $url, $result)
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($baseUrl);
        $this->assertEquals($/* Replaced /* Replaced /* Replaced client */ */ */->get($url)->getUrl(), $result);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client
     */
    public function testAllowsConfigsToBeChangedAndInjectedInBaseUrl()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://{{a}}/{{b}}');
        $this->assertEquals('http:///', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl());
        $this->assertEquals('http://{{a}}/{{b}}', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl(false));
        $/* Replaced /* Replaced /* Replaced client */ */ */->setConfig(array(
            'a' => 'test.com',
            'b' => 'index.html'
        ));
        $this->assertEquals('http://test.com/index.html', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::createRequest
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
        $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig()->set('a', 1)->set('b', 2);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('PUT', '/path/{{a}}?q={{b}}');
        $this->assertEquals($request->getUrl(), $this->getServer()->getUrl() . 'path/1?q=2');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::get
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::delete
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::head
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::put
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::post
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::options
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
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::createRequest
     */
    public function testClientInjectsConfigsIntoUrls()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.test.com/api/v1', array(
            'test' => '123'
        ));
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get('relative/{{test}}');
        $this->assertEquals('http://www.test.com/api/v1/relative/123', $request->getUrl());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client
     */
    public function testAllowsEmptyBaseUrl()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://www.google.com/');
        $this->assertEquals('http://www.google.com/', $request->getUrl());
        $request->setResponse(new Response(200), true);
        $request->send();
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::send
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::setCurlMulti
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::getCurlMulti
     */
    public function testAllowsCustomCurlMultiObjects()
    {
        $mock = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Http\\Curl\\CurlMulti', array('add', 'send'));
        $mock->expects($this->once())
             ->method('add');
        $mock->expects($this->once())
             ->method('send');

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->setCurlMulti($mock);

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get();
        $request->setResponse(new Response(200), true);
        $/* Replaced /* Replaced /* Replaced client */ */ */->send($request);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::send
     */
    public function testClientSendsMultipleRequests()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $mock = new MockPlugin();

        $responses = array(
            new Response(200),
            new Response(201),
            new Response(202)
        );

        $mock->addResponse($responses[0]);
        $mock->addResponse($responses[1]);
        $mock->addResponse($responses[2]);

        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($mock);

        $requests = array(
            $/* Replaced /* Replaced /* Replaced client */ */ */->get(),
            $/* Replaced /* Replaced /* Replaced client */ */ */->head(),
            $/* Replaced /* Replaced /* Replaced client */ */ */->put('/', null, 'test')
        );

        $this->assertEquals(array(
            $responses[0],
            $responses[1],
            $responses[2]
        ), $/* Replaced /* Replaced /* Replaced client */ */ */->send($requests));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::send
     */
    public function testClientSendsSingleRequest()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $mock = new MockPlugin();
        $response = new Response(200);
        $mock->addResponse($response);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($mock);
        $this->assertEquals($response, $/* Replaced /* Replaced /* Replaced client */ */ */->send($/* Replaced /* Replaced /* Replaced client */ */ */->get()));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::send
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\BadResponseException
     */
    public function testClientThrowsExceptionForSingleRequest()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $mock = new MockPlugin();
        $response = new Response(404);
        $mock->addResponse($response);
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($mock);
        $/* Replaced /* Replaced /* Replaced client */ */ */->send($/* Replaced /* Replaced /* Replaced client */ */ */->get());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::send
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\ExceptionCollection
     */
    public function testClientThrowsExceptionForMultipleRequests()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client($this->getServer()->getUrl());
        $mock = new MockPlugin();
        $mock->addResponse(new Response(200));
        $mock->addResponse(new Response(404));
        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($mock);
        $/* Replaced /* Replaced /* Replaced client */ */ */->send(array($/* Replaced /* Replaced /* Replaced client */ */ */->get(), $/* Replaced /* Replaced /* Replaced client */ */ */->head()));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client
     */
    public function testQueryStringsAreNotDoubleEncoded()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://test.com', array(
            'path'  => array('foo', 'bar'),
            'query' => 'hi there',
            'data'  => array(
                'test' => 'a&b'
            )
        ));

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get('{/path*}{?query,data*}');
        $this->assertEquals('http://test.com/foo/bar?query=hi%20there&test=a%26b', $request->getUrl());
        $this->assertEquals('hi there', $request->getQuery()->get('query'));
        $this->assertEquals('a&b', $request->getQuery()->get('test'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client
     */
    public function testQueryStringsAreNotDoubleEncodedUsingAbsolutePaths()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://test.com', array(
            'path'  => array('foo', 'bar'),
            'query' => 'hi there',
        ));
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get('http://test.com{?query}');
        $this->assertEquals('http://test.com/?query=hi%20there', $request->getUrl());
        $this->assertEquals('hi there', $request->getQuery()->get('query'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::setUriTemplate
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::getUriTemplate
     */
    public function testAllowsUriTemplateInjection()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://test.com', array(
            'path'  => array('foo', 'bar'),
            'query' => 'hi there',
        ));

        $a = $/* Replaced /* Replaced /* Replaced client */ */ */->getUriTemplate();
        $this->assertSame($a, $/* Replaced /* Replaced /* Replaced client */ */ */->getUriTemplate());
        $/* Replaced /* Replaced /* Replaced client */ */ */->setUriTemplate(new UriTemplate());
        $this->assertNotSame($a, $/* Replaced /* Replaced /* Replaced client */ */ */->getUriTemplate());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::expandTemplate
     */
    public function testAllowsCustomVariablesWhenExpandingTemplates()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://test.com', array(
            'test' => 'hi',
        ));

        $uri = $/* Replaced /* Replaced /* Replaced client */ */ */->expandTemplate('http://{test}{?query*}', array(
            'query' => array(
                'han' => 'solo'
            )
        ));

        $this->assertEquals('http://hi?han=solo', $uri);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::createRequest
     * @expectedException InvalidArgumentException
     */
    public function testUriArrayMustContainExactlyTwoElements()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', array('haha!'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::createRequest
     * @expectedException InvalidArgumentException
     */
    public function testUriArrayMustContainAnArray()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', array('haha!', 'test'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::createRequest
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::get
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::put
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::post
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::head
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::options
     */
    public function testUriArrayAllowsCustomTemplateVariables()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $vars = array(
            'var' => 'hi'
        );
        $this->assertEquals('/hi', (string) $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', array('/{var}', $vars))->getUrl());
        $this->assertEquals('/hi', (string) $/* Replaced /* Replaced /* Replaced client */ */ */->get(array('/{var}', $vars))->getUrl());
        $this->assertEquals('/hi', (string) $/* Replaced /* Replaced /* Replaced client */ */ */->put(array('/{var}', $vars))->getUrl());
        $this->assertEquals('/hi', (string) $/* Replaced /* Replaced /* Replaced client */ */ */->post(array('/{var}', $vars))->getUrl());
        $this->assertEquals('/hi', (string) $/* Replaced /* Replaced /* Replaced client */ */ */->head(array('/{var}', $vars))->getUrl());
        $this->assertEquals('/hi', (string) $/* Replaced /* Replaced /* Replaced client */ */ */->options(array('/{var}', $vars))->getUrl());
    }
}