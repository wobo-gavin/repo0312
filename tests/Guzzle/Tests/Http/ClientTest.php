<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Log\ClosureLogAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser\UriTemplate\UriTemplate;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Log\LogPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Mock\MockPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlMulti;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Version;

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
        $this->assertSame($/* Replaced /* Replaced /* Replaced client */ */ */, $/* Replaced /* Replaced /* Replaced client */ */ */->setBaseUrl('http://www.test.com/{test}'));
        $this->assertEquals('http://www.test.com/123', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl());
        $this->assertEquals('http://www.test.com/{test}', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl(false));

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
        $this->assertEquals('Testing...api/v1/key/value', $/* Replaced /* Replaced /* Replaced client */ */ */->expandTemplate('Testing...api/{api}/key/{key}'));

        // Make sure that the /* Replaced /* Replaced /* Replaced client */ */ */ properly validates and injects config
        $this->assertEquals('bar', $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('foo'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::createRequest
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException
     */
    public function testValidatesArrayForTemplateIsValid()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.google.com/');
        $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', array('foo' => 'bar', 'baz' => 'bam'));
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
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.{foo}.{data}/', array(
            'data' => '123',
            'foo' => 'bar'
        ));
        $this->assertEquals('http://www.bar.123/', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl());
        $/* Replaced /* Replaced /* Replaced client */ */ */->setBaseUrl('http://www.google.com/');
        $this->assertEquals('http://www.google.com/', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl());
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
            'curl.options' => array(
                'CURLOPT_HTTPAUTH'     => 'CURLAUTH_DIGEST',
                'abc'                  => 'foo',
                'blacklist'            => 'abc',
                'debug'                => true
            )
        ));

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest();
        $options = $request->getCurlOptions();
        $this->assertEquals(CURLAUTH_DIGEST, $options->get(CURLOPT_HTTPAUTH));
        $this->assertEquals('foo', $options->get('abc'));
        $this->assertEquals('abc', $options->get('blacklist'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::setSslVerification
     */
    public function testClientAllowsFineGrainedSslControlButIsSecureByDefault()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('https://www.secure.com/');

        // secure by default
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest();
        $options = $request->getCurlOptions();
        $this->assertTrue($options->get(CURLOPT_SSL_VERIFYPEER));

        // set a capath if you prefer
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('https://www.secure.com/');
        $/* Replaced /* Replaced /* Replaced client */ */ */->setSslVerification(__DIR__);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest();
        $options = $request->getCurlOptions();
        $this->assertSame(__DIR__, $options->get(CURLOPT_CAPATH));
    }
    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::__construct
     */
    public function testConfigSettingsControlSslConfiguration()
    {
        // Use the default ca certs on the system
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('https://www.secure.com/', array('ssl.certificate_authority' => 'system'));
        $this->assertNull($/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('curl.options'));
        // Can set the cacert value as well
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('https://www.secure.com/', array('ssl.certificate_authority' => false));
        $options = $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('curl.options');
        $this->assertArrayNotHasKey(CURLOPT_CAINFO, $options);
        $this->assertSame(false, $options[CURLOPT_SSL_VERIFYPEER]);
        $this->assertSame(2, $options[CURLOPT_SSL_VERIFYHOST]);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::setSslVerification
     */
    public function testClientAllowsUnsafeOperationIfRequested()
    {
        // be really unsafe if you insist
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('https://www.secure.com/', array(
            'api' => 'v1'
        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */->setSslVerification(false);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest();
        $options = $request->getCurlOptions();
        $this->assertFalse($options->get(CURLOPT_SSL_VERIFYPEER));
        $this->assertNull($options->get(CURLOPT_CAINFO));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::setSslVerification
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\RuntimeException
     */
    public function testThrowsExceptionForInvalidCertificate()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('https://www.secure.com/');
        $/* Replaced /* Replaced /* Replaced client */ */ */->setSslVerification('/path/to/missing/file');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::setSslVerification
     */
    public function testClientAllowsSettingSpecificSslCaInfo()
    {
        // set a file other than the provided cacert.pem
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('https://www.secure.com/', array(
            'api' => 'v1'
        ));

        $/* Replaced /* Replaced /* Replaced client */ */ */->setSslVerification(__FILE__);
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest();
        $options = $request->getCurlOptions();
        $this->assertSame(__FILE__, $options->get(CURLOPT_CAINFO));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::setSslVerification
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException
     */
    public function testClientPreventsInadvertentInsecureVerifyHostSetting()
    {
        // set a file other than the provided cacert.pem
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('https://www.secure.com/', array(
            'api' => 'v1'
        ));
        $/* Replaced /* Replaced /* Replaced client */ */ */->setSslVerification(__FILE__, true, true);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::setSslVerification
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException
     */
    public function testClientPreventsInvalidVerifyPeerSetting()
    {
        // set a file other than the provided cacert.pem
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('https://www.secure.com/', array(
            'api' => 'v1'
        ));
        $/* Replaced /* Replaced /* Replaced client */ */ */->setSslVerification(__FILE__, 'yes');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::prepareRequest
     */
    public function testClientAddsParamsToRequests()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://www.example.com', array(
            'api' => 'v1',
            'request.params' => array(
                'foo' => 'bar',
                'baz' => 'jar'
            )
        ));
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest();
        $this->assertEquals('bar', $request->getParams()->get('foo'));
        $this->assertEquals('jar', $request->getParams()->get('baz'));
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
            array($u2, 'relative/path/to/resource', $this->getServer()->getUrl() . 'base/relative/path/to/resource'),
            array($u2, 'relative/path/to/resource?another=query', $this->getServer()->getUrl() . 'base/relative/path/to/resource?another=query')
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
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client('http://{a}/{b}');
        $this->assertEquals('http:///', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl());
        $this->assertEquals('http://{a}/{b}', $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl(false));
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
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('PUT', '/path/{a}?q={b}');
        $this->assertEquals($request->getUrl(), $this->getServer()->getUrl() . 'path/1?q=2');
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::get
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::delete
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::head
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::put
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::post
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::options
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::patch
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
        $this->assertEquals('PATCH', $/* Replaced /* Replaced /* Replaced client */ */ */->patch()->getMethod());
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
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->get('relative/{test}');
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
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\BadResponseException
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
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\ExceptionCollection
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
        $this->assertEquals('http://test.com?query=hi%20there', $request->getUrl());
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

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::setDefaultHeaders
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::getDefaultHeaders
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::createRequest
     */
    public function testAllowsDefaultHeaders()
    {
        $default = array(
            'X-Test' => 'Hi!'
        );
        $other = array(
            'X-Other' => 'Foo'
        );

        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->setDefaultHeaders($default);
        $this->assertEquals($default, $/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultHeaders()->getAll());
        $/* Replaced /* Replaced /* Replaced client */ */ */->setDefaultHeaders(new Collection($default));
        $this->assertEquals($default, $/* Replaced /* Replaced /* Replaced client */ */ */->getDefaultHeaders()->getAll());

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', null, $other);
        $this->assertEquals('Hi!', $request->getHeader('X-Test'));
        $this->assertEquals('Foo', $request->getHeader('X-Other'));

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', null, new Collection($other));
        $this->assertEquals('Hi!', $request->getHeader('X-Test'));
        $this->assertEquals('Foo', $request->getHeader('X-Other'));

        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET');
        $this->assertEquals('Hi!', $request->getHeader('X-Test'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::setDefaultHeaders
     * @expectedException InvalidArgumentException
     */
    public function testValidatesDefaultHeaders()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */->setDefaultHeaders('foo');
    }

    public function testDontReuseCurlMulti()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */1 = new Client();
        $/* Replaced /* Replaced /* Replaced client */ */ */2 = new Client();
        $this->assertNotSame($/* Replaced /* Replaced /* Replaced client */ */ */1->getCurlMulti(), $/* Replaced /* Replaced /* Replaced client */ */ */2->getCurlMulti());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::getDefaultUserAgent
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::setUserAgent
     */
    public function testGetDefaultUserAgent()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $agent = $this->readAttribute($/* Replaced /* Replaced /* Replaced client */ */ */, 'userAgent');
        $version = curl_version();
        $testAgent = sprintf('/* Replaced /* Replaced /* Replaced Guzzle */ */ *//%s curl/%s PHP/%s', Version::VERSION, $version['version'], PHP_VERSION);
        $this->assertEquals($agent, $testAgent);

        $/* Replaced /* Replaced /* Replaced client */ */ */->setUserAgent('foo');
        $this->assertEquals('foo', $this->readAttribute($/* Replaced /* Replaced /* Replaced client */ */ */, 'userAgent'));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client::prepareRequest
     */
    public function testOverwritesUserAgent()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://www.foo.com', array('User-agent' => 'foo'));
        $this->assertEquals('foo', (string) $request->getHeader('User-Agent'));
    }

    public function testUsesDefaultUserAgent()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', 'http://www.foo.com');
        $this->assertContains('/* Replaced /* Replaced /* Replaced Guzzle */ */ *//', (string) $request->getHeader('User-Agent'));
    }
}
