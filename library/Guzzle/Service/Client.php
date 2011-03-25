<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inflector;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inspector;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event\Observer;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event\AbstractSubject;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\NullObject;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlConstants;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandSet;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription;

/**
 * Client object for executing commands on a webservice.
 *
 * Signals emitted:
 *
 *  event                   context           description
 *  -----                   -------           -----------
 *  request.create          RequestInterface  Created a new request
 *  command.before_send     CommandInterface  A command is about to execute
 *  command.after_send      CommandInterface  A command executed
 *  command.create          CommandInterface  A command was created
 *
 * @author  michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org
 */
class Client extends AbstractSubject
{
    /**
     * @var ServiceDescription Description of the service and possible commands
     */
    protected $serviceDescription;

    /**
     * @var string Your application's name and version (e.g. MyApp/1.0)
     */
    protected $userApplication = null;

    /**
     * @var Collection Parameter object holding configuration data
     */
    private $config;

    /**
     * @var Url Base URL of the /* Replaced /* Replaced /* Replaced client */ */ */
     */
    private $baseUrl;

    /**
     * @var Url Cached injected base URL
     */
    private $injectedBaseUrl;

    /**
     * Basic factory method to create a new /* Replaced /* Replaced /* Replaced client */ */ */.  Extend this method in
     * subclasses to build more complex /* Replaced /* Replaced /* Replaced client */ */ */s.
     *
     * @param array|Collection $config (optiona) Configuartion data
     *
     * @return Client
     */
    public static function factory($config)
    {
        return new self($config['base_url'], $config);
    }
    
    /**
     * Client constructor
     *
     * @param string $baseUrl Base URL used to interact with a web service
     * @param array|Collection $config (optional) Configuration settings
     */
    public function __construct($baseUrl, $config = null)
    {
        if ($config) {
            $this->setConfig($config);
        }
        $this->setBaseUrl($baseUrl);
    }

    /**
     * Set the configuration object to use with the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param array|Collection|string $config Parameters that define how the
     *      /* Replaced /* Replaced /* Replaced client */ */ */ behaves and connects to a webservice.  Pass an array or a
     *      Collection object.
     *
     * @return Client
     */
    public final function setConfig($config)
    {
        // Set the configuration object
        if ($config instanceof Collection) {
            $this->config = $config;
        } else if (is_array($config)) {
            $this->config = new Collection($config);
        } else {
            throw new \InvalidArgumentException(
                'Config must be an array or Collection'
            );
        }

        $this->setBaseUrl($this->baseUrl);

        return $this;
    }

    /**
     * Get a configuration setting or all of the configuration settings
     *
     * @param bool|string $key Configuration value to retrieve.  Set to FALSE
     *      to retrieve all values of the /* Replaced /* Replaced /* Replaced client */ */ */.  The object return can be
     *      modified, and modifications will affect the /* Replaced /* Replaced /* Replaced client */ */ */'s config.
     *
     * @return mixed|Collection
     */
    public final function getConfig($key = false)
    {
        if (!$this->config) {
            $this->config = new Collection();
        }

        if ($key) {
            return $this->config->get($key);
        } else {
            return $this->config;
        }
    }

    /**
     * Inject configuration values into a formatted string with {{param}} as a
     * parameter delimiter (replace param with the configuration value name)
     *
     * @param string $string String to inject config values into
     *
     * @return string
     */
    public final function inject($string)
    {
        return /* Replaced /* Replaced /* Replaced Guzzle */ */ */::inject($string, $this->getConfig());
    }

    /**
     * Create and return a new {@see RequestInterface} configured for the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string $method (optional) HTTP method.  Defaults to GET
     * @param string $uri (optional) Resource URI.  Use an absolute path to
     *      override the base path of the /* Replaced /* Replaced /* Replaced client */ */ */, or a relative path to append
     *      to the base path of the /* Replaced /* Replaced /* Replaced client */ */ */.  The URI can contain the
     *      querystring as well.
     * @param array|Collection (optional) Parameters to replace from the uri
     *      {{}} injection points.
     *
     * @return RequestInterface
     */
    public function createRequest($method = RequestInterface::GET, $uri = null, $inject = null)
    {
        // Inject configuration data into the URI if needed
        if ($inject) {
            if (is_array($inject)) {
                $inject = new Collection($inject);
            }
            $uri = /* Replaced /* Replaced /* Replaced Guzzle */ */ */::inject($uri, $inject);
        }

        if ($uri) {
            // Use absolute URLs as-is
            if (strpos($uri, 'http') === 0) {
                $url = $uri;
            } else {
                $this->getBaseUrl();
                $url = clone $this->injectedBaseUrl;
                $url = (string) $url->combine($uri);
            }
        } else {
            $url = $this->getBaseUrl();
        }

        return $this->prepareRequest(
            RequestFactory::create($method, $url)
        );
    }

    /**
     * Prepare a request to be sent from the Client by adding /* Replaced /* Replaced /* Replaced client */ */ */ specific
     * behaviors and properties to the request.
     *
     * This method should only be called when using the default RequestFactory
     * is not an option and the request sent from the /* Replaced /* Replaced /* Replaced client */ */ */ must be created
     * manually.
     *
     * @param RequestInterface $request Request to prepare for the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @return RequestInterface
     */
    public function prepareRequest(RequestInterface $request)
    {
        // Add your application name to the request
        if ($this->userApplication) {
            $request->setHeader('User-Agent', trim($this->userApplication . ' ' . $request->getHeader('User-Agent')));
        }

        // Add any curl options that might in the config to the request
        foreach ($this->getConfig()->getAll('/^curl\..+/', Collection::MATCH_REGEX) as $key => $value) {
            $curlOption = CurlConstants::getOptionInt(str_replace('curl.', '', $key));
            if ($curlOption !== false) {
                $curlValue = CurlConstants::getValueInt($value);
                $curlValue = ($curlValue === false) ? $value : $curlValue;
                $request->getCurlOptions()->set($curlOption, $curlValue);
            }
        }

        // Add the cache key filter to requests if one is set on the /* Replaced /* Replaced /* Replaced client */ */ */
        if ($this->getConfig('cache.key_filter')) {
            $request->getParams()->set('cache.key_filter', $this->getConfig('cache.key_filter'));
        }

        // Attach /* Replaced /* Replaced /* Replaced client */ */ */ observers to the request
        $reqManager = $request->getEventManager();
        $manager = $this->getEventManager();
        foreach ($manager->getAttached() as $observer) {
            $reqManager->attach($observer, $manager->getPriority($observer));
        }

        $manager->notify('request.create', $request);

        return $request;
    }

    /**
     * Get a command by name.  First, the /* Replaced /* Replaced /* Replaced client */ */ */ will see if it has a service
     * description and if the service description defines a command by the
     * supplied name.  If no dynamic command is found, the /* Replaced /* Replaced /* Replaced client */ */ */ will look for
     * a concrete command class exists matching the name supplied.  If neither
     * are found, an InvalidArgumentException is thrown.
     *
     * @param string $name Name of the command to retrieve
     * @param array $args (optional) Arguments to pass to the command
     *
     * @return CommandInterface
     * @throws InvalidArgumentException if no command can be found by name
     */
    public function getCommand($name, array $args = array())
    {
        $command = null;

        // If a service description is present, see if a command is defined
        if ($this->serviceDescription && $this->serviceDescription->hasCommand($name)) {
            $command = $this->serviceDescription->createCommand($name, $args);
        }

        // Check if a concrete command exists using inflection
        if (!$command) {
            // Determine the class to instantiate based on the namespace of the
            // current /* Replaced /* Replaced /* Replaced client */ */ */ and the default location of commands
            $prefix = $this->getConfig('command.prefix');
            if (!$prefix) {
                // The prefix can be specified in a factory method and is cached
                $prefix = implode('\\', array_slice(explode('\\', get_class($this)), 0, -1)) . '\\Command\\';
                $this->getConfig()->set('command.prefix', $prefix);
            }

            $class = $prefix . str_replace(' ', '\\', ucwords(str_replace('.', ' ', Inflector::camel($name))));

            // Create the concrete command if it exists
            if (class_exists($class)) {
                $command = new $class($args);
            }
        }

        if (!$command) {
            throw new \InvalidArgumentException("$name command could not be found");
        }

        $command->setClient($this);
        $this->getEventManager()->notify('command.create', $command);

        return $command;
    }

    /**
     * Execute a command and return the response
     *
     * @param CommandInterface|CommandSet $command The command or set to execute
     *
     * @return mixed Returns the result of the executed command's
     *       {@see CommandInterface::getResult} method if a CommandInterface is
     *       passed, or the CommandSet itself if a CommandSet is passed
     * @throws InvalidArgumentException if an invalid command is passed
     * @throws Command\CommandSetException if a set contains commands associated
     *      with other /* Replaced /* Replaced /* Replaced client */ */ */s
     */
    public function execute($command)
    {
        if ($command instanceof CommandInterface) {

            $command->prepare($this);
            $this->getEventManager()->notify('command.before_send', $command);
            $command->getRequest()->send();
            $this->getEventManager()->notify('command.after_send', $command);

            return $command->getResult();

        } else if ($command instanceof CommandSet) {

            foreach ($command as $c) {
                if ($c->getClient() && $c->getClient() !== $this) {
                    throw new Command\CommandSetException(
                        'Attempting to run a mixed-Client CommandSet from a ' .
                        'Client context.  Run the set using CommandSet::execute() '
                    );
                }
                $c->setClient($this);
            }

            return $command->execute();

        } else {
            throw new \InvalidArgumentException('Invalid command sent to ' . __METHOD__);
        }
    }

    /**
     * Get the base service endpoint URL with configuration options injected
     * into the configuration setting.
     *
     * @param bool $inject (optional) Set to FALSE to get the raw base URL
     *
     * @return string
     * @throws RuntimeException if a base URL has not been set
     */
    public function getBaseUrl($inject = true)
    {
        if (!$this->baseUrl) {
            throw new \RuntimeException('A base URL has not been set');
        }

        return $inject ? $this->inject((string) $this->baseUrl) : $this->baseUrl;
    }

    /**
     * Set the base service endpoint URL
     *
     * @param string $url The base service endpoint URL of the webservice
     *
     * @return Client
     */
    public final function setBaseUrl($url)
    {
        $this->baseUrl = $url;
        if ($this->config || strpos($url, '{{') === false) {
            $this->injectedBaseUrl = Url::factory($this->inject($url));
        }

        return $this;
    }

    /**
     * Set the service description of the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param ServiceDescription $description Service description that describes
     *      all of the commands and information of the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @return Client
     */
    public final function setDescription(ServiceDescription $service)
    {
        $this->serviceDescription = $service;

        return $this;
    }

    /**
     * Get the service description of the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @return ServiceDescription|NullObject
     */
    public final function getDescription()
    {
        return $this->serviceDescription ?: new NullObject();
    }

    /**
     * Set the name of your application and application version that will be
     * appended to the User-Agent header of all reqeusts.
     *
     * @param string $appName Name of your application
     * @param string $version Version number of your application
     *
     * @return Client
     */
    public final function setUserApplication($appName, $version)
    {
        $this->userApplication = $appName . '/' . ($version ?: '1.0');

        return $this;
    }

    /**
     * Create a GET request for the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string $uri (optional) Resource URI of the request.  Use an
     *      absolute path to override the base path, or a relative path to append
     * @param array|Collection (optional) Parameters to replace from the uri
     *      {{}} injection points.
     *
     * @return Request
     */
    public final function get($uri = null, $inject = null)
    {
        return $this->createRequest('GET', $uri, $inject);
    }

    /**
     * Create a HEAD request for the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string $uri (optional) Resource URI of the request.  Use an
     *      absolute path to override the base path, or a relative path to append
     * @param array|Collection (optional) Parameters to replace from the uri
     *      {{}} injection points.
     *
     * @return Request
     */
    public final function head($uri = null, $inject = null)
    {
        return $this->createRequest('HEAD', $uri, $inject);
    }

    /**
     * Create a DELETE request for the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string $uri (optional) Resource URI of the request.  Use an
     *      absolute path to override the base path, or a relative path to append
     * @param array|Collection (optional) Parameters to replace from the uri
     *      {{}} injection points.
     *
     *
     * @return Request
     */
    public final function delete($uri = null, $inject = null)
    {
        return $this->createRequest('DELETE', $uri, $inject);
    }

    /**
     * Create a PUT request for the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string $uri (optional) Resource URI of the request.  Use an
     *      absolute path to override the base path, or a relative path to append
     * @param array|Collection (optional) Parameters to replace from the uri
     *      {{}} injection points.
     *
     * @return EntityEnclosingRequest
     */
    public final function put($uri = null, $inject = null)
    {
        return $this->createRequest('PUT', $uri, $inject);
    }

    /**
     * Create a POST request for the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string $uri (optional) Resource URI of the request.  Use an absolute path to
     *      override the base path, or a relative path to append it.
     * @param array|Collection (optional) Parameters to replace from the uri
     *      {{}} injection points.
     *
     * @return EntityEnclosingRequest
     */
    public final function post($uri = null, $inject = null)
    {
        return $this->createRequest('POST', $uri, $inject);
    }

    /**
     * Create an OPTIONS request for the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string $uri (optional) Resource URI of the request.  Use an
     *      absolute path to override the base path, or relative path to append
     * @param array|Collection (optional) Parameters to replace from the uri
     *      {{}} injection points.
     *
     * @return Request
     */
    public final function options($uri = null, $inject = null)
    {
        return $this->createRequest('OPTIONS', $uri, $inject);
    }
}