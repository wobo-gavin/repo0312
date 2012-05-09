<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\HasDispatcherInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Adapter\ZendLogAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\MockPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceBuilder;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Mock\MockObserver;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Server;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Base testcase class for all /* Replaced /* Replaced /* Replaced Guzzle */ */ */ testcases.
 */
abstract class /* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase extends \PHPUnit_Framework_TestCase
{
    protected static $mockBasePath;
    public static $serviceBuilder;
    public static $server;

    private $requests = array();
    public $mockObserver;

    /**
     * Get the global server object used throughout the unit tests of /* Replaced /* Replaced /* Replaced Guzzle */ */ */
     *
     * @return Server
     */
    public function getServer()
    {
        if (!self::$server) {
            try {
                self::$server = new Server();
                if (self::$server->isRunning()) {
                    self::$server->flush();
                } else {
                    self::$server->start();
                }
            } catch (\Exception $e) {
                fwrite(STDERR, $e->getMessage());
            }
        }

        return self::$server;
    }

    /**
     * Set the service builder to use for tests
     *
     * @param ServiceBuilder $builder Service builder
     */
    public static function setServiceBuilder(ServiceBuilder $builder)
    {
        self::$serviceBuilder = $builder;
    }

    /**
     * Get a service builder object that can be used throughout the service tests
     *
     * @return ServiceBuilder
     */
    public function getServiceBuilder()
    {
        if (!self::$serviceBuilder) {
            throw new RuntimeException('No service builder has been set via setServiceBuilder()');
        }

        return self::$serviceBuilder;
    }

    /**
     * Check if an event dispatcher has a subscriber
     *
     * @param HasDispatcherInterface $dispatcher
     * @param EventSubscriberInterface $subscriber
     *
     * @return bool
     */
    protected function hasSubscriber(HasDispatcherInterface $dispatcher, EventSubscriberInterface $subscriber)
    {
        $class = get_class($subscriber);
        $all = array_keys(call_user_func(array($class, 'getSubscribedEvents')));

        foreach ($all as $i => $event) {
            foreach ($dispatcher->getEventDispatcher()->getListeners($event) as $e) {
                if ($e[0] === $subscriber) {
                    unset($all[$i]);
                    break;
                }
            }
        }

        return count($all) == 0;
    }

    /**
     * Get a wildcard observer for an event dispatcher
     *
     * @param HasEventDispatcherInterface $hasEvent
     *
     * @return MockObserver
     */
    public function getWildcardObserver(HasDispatcherInterface $hasDispatcher)
    {
        $class = get_class($hasDispatcher);
        $o = new MockObserver();
        $events = call_user_func(array($class, 'getAllEvents'));
        foreach ($events as $event) {
            $hasDispatcher->getEventDispatcher()->addListener($event, array($o, 'update'));
        }

        return $o;
    }

    /**
     * Set the mock response base path
     *
     * @param string $path Path to mock response folder
     *
     * @return /* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
     */
    public static function setMockBasePath($path)
    {
        self::$mockBasePath = $path;
    }

    /**
     * Mark a request as being mocked
     *
     * @param RequestInterface $request
     */
    public function addMockedRequest(RequestInterface $request)
    {
        $this->requests[] = $request;

        return $this;
    }

    /**
     * Get all of the mocked requests
     *
     * @return array
     */
    public function getMockedRequests()
    {
        return $this->requests;
    }

    /**
     * Get a mock response for a /* Replaced /* Replaced /* Replaced client */ */ */ by mock file name
     *
     * @param string $path Relative path to the mock response file
     *
     * @return Response
     */
    public function getMockResponse($path)
    {
        return MockPlugin::getMockFile(self::$mockBasePath . DIRECTORY_SEPARATOR . $path);
    }

    /**
     * Set a mock response from a mock file on the next /* Replaced /* Replaced /* Replaced client */ */ */ request.
     *
     * This method assumes that mock response files are located under the
     * Command/Mock/ directory of the Service being tested
     * (e.g. Unfuddle/Command/Mock/).  A mock response is added to the next
     * request sent by the /* Replaced /* Replaced /* Replaced client */ */ */.
     *
     * @param Client $/* Replaced /* Replaced /* Replaced client */ */ */ Client object to modify
     * @param string $paths  Path to files within the Mock folder of the service
     *
     * @return MockPlugin returns the created mock plugin
     */
    public function setMockResponse(Client $/* Replaced /* Replaced /* Replaced client */ */ */, $paths)
    {
        $this->requests = array();
        $that = $this;
        $mock = new MockPlugin(null, true);
        $mock->getEventDispatcher()->addListener('mock.request', function(Event $event) use ($that) {
            $that->addMockedRequest($event['request']);
        });

        foreach ((array) $paths as $path) {
            $mock->addResponse($this->getMockResponse($path));
        }

        $/* Replaced /* Replaced /* Replaced client */ */ */->getEventDispatcher()->addSubscriber($mock);

        return $mock;
    }

    /**
     * Check if an array of HTTP headers matches another array of HTTP headers
     * while taking * into account as a wildcard for header values
     *
     * @param array $expected Expected HTTP headers (allows wildcard values)
     * @param array|Collection $actual Actual HTTP header array
     * @param array $ignore (optional) Headers to ignore from the comparison
     * @param array $absent (optional) Array of headers that must not be present
     *
     * @return array|false Returns an array of the differences or FALSE if none
     */
    public function compareHttpHeaders(array $expected, $actual, array $ignore = array(), array $absent = array())
    {
        $differences = array();

        // Add information about headers that were present but weren't supposed to be
        foreach ($absent as $header) {
            if (isset($actual[$header])) {
                $differences["unexpected_{$header}"] = $actual[$header];
            }
        }

        // Compare the expected and actual HTTP headers in no particular order
        foreach ($actual as $key => $value) {

            if (in_array($key, $ignore)) {
                continue;
            }

            if (!isset($expected[$key])) {
                $differences[$key] = $value;
                continue;
            }

            // Check values and take wildcards into account
            $pos = strpos($expected[$key], '*');
            foreach ((array) $actual[$key] as $v) {
                if (($pos === false && $v != $expected[$key]) || $pos > 0 && substr($v, 0, $pos) != substr($expected[$key], 0, $pos)) {
                    $differences[$key] = $value;
                }
            }
        }

        return empty($differences) ? false : $differences;
    }

    /**
     * Compare HTTP headers and use special markup to filter values
     * A header prefixed with '!' means it must not exist
     * A header prefixed with '_' means it must be ignored
     * A header value of '*' means anything after the * will be ignored
     *
     * @param array $filteredHeaders Array of special headers
     * @param array $actualHeaders Array of headers to check against
     *
     * @return array|false Returns an array of the differences or FALSE if none
     */
    public function filterHeaders($filteredHeaders, $actualHeaders)
    {
        $expected = array();
        $ignore = array();
        $absent = array();

        foreach ($filteredHeaders as $k => $v) {
            if ($k[0] == '_') {
                // This header should be ignored
                $ignore[] = str_replace('_', '', $k);
            } else if ($k[0] == '!') {
                // This header must not be present
                $absent[] = str_replace('!', '', $k);
            } else {
                $expected[$k] = $v;
            }
        }

        return $this->compareHttpHeaders($expected, $actualHeaders, $ignore, $absent);
    }
}
