<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inspector;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Injector;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\Chain;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event\AbstractSubject;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\EntityBody;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlConstants;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Plugin\AbstractPlugin;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandSet;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandFactoryInterface;

/**
 * Client object for executing commands on a webservice.
 *
 * Available events states:
 *
 *  # request.create - When a request is created, the
 *      {@see RequestInterface} will be set as the state context
 *
 *  # request.factory.set - When the request factory is set, overriding the
 *      default RequestFactory the {@see RequestFactory} prototype will be
 *      set as the state context
 *
 *  # command.before_execute - Called before executing a command.  The
 *      {@see CommandInterface} will be set as the state context
 *
 *  # command.after_execute - Called after executing a command.  The
 *      {@see CommandInterface} will be set as the state context
 *
 *  # command.create - Called when a command was dynamically created.  The
 *      {@see CommandInterface} will be set as the state context
 *
 * @author  michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org
 */
class Client extends AbstractSubject
{
    /**
     * @var Collection Parameter object holding configuration data
     */
    protected $config;

    /**
     * @var ServiceDescription Description of the service and possible commands
     */
    protected $serviceDescription;

    /**
     * @var CommandFactoryInterface Factory used to build API commands
     */
    protected $commandFactory;

    /**
     * @var RequestFactory Request factory used to create new /* Replaced /* Replaced /* Replaced client */ */ */ requests
     */
    protected $requestFactory;

    /**
     * @var array Plugins attached to the /* Replaced /* Replaced /* Replaced client */ */ */ which will be attached to requests
     */
    protected $plugins = array();

    /**
     * @var string Your application's name and version (e.g. MyApp/1.0)
     */
    protected $userApplication = null;

    /**
     * Client constructor
     *
     * @param array|Collection $config Parameters that define how the /* Replaced /* Replaced /* Replaced client */ */ */
     *      behaves and connects to a webservice
     * @param ServiceDescription $serviceDescription Description of the service
     *      and the commands that can be taken on the webservice
     * @param CommandFactoryInterface $commandFactory Command factory used to
     *      create commands by name.
     *
     * @throws ServiceException
     */
    public function __construct($config, ServiceDescription $serviceDescription, CommandFactoryInterface $commandFactory)
    {
        // Set the configuration object
        if ($config instanceof Collection) {
            $this->config = $config;
        } else if (is_array($config)) {
            $this->config = new Collection($config);
        } else {
            throw new ServiceException('Invalid config argument passed to ' . __METHOD__);
        }

        $this->serviceDescription = $serviceDescription;
        $this->commandFactory = $commandFactory;

        if (!$this->getConfig('base_url')) {
            $this->config->set('base_url', $serviceDescription->getBaseUrl());
        }

        // Add default arguments and validate the supplied arguments
        Inspector::getInstance()->validateConfig($serviceDescription->getClientArgs(), $this->config, true);

        // Make sure that the service has a base_url specified
        if (!$this->config->get('base_url')) {
            throw new ServiceException('No base_url argument was supplied to the constructor');
        }

        // Set the default request factory
        $this->setRequestFactory(RequestFactory::getInstance());

        $this->init();
    }

    /**
     * Get the short name of a plugin
     *
     * @param AbstractPlugin|string $plugin Plugin to shorten
     *
     * @return string
     */
    public static function getShortPluginName($plugin)
    {
        $class = (is_object($plugin)) ? get_class($plugin) : $plugin;

        return basename(str_replace('\\', '/', $class));
    }

    /**
     * Get a configuration setting from the /* Replaced /* Replaced /* Replaced client */ */ */ or all of the configuration
     * settings.  This command should not allow the /* Replaced /* Replaced /* Replaced client */ */ */ config to be
     * modified, so an immutable value is returned
     *
     * @param bool|string $key Configuration value to retrieve.  Set to FALSE
     *      to retrieve all values as an array.
     *
     * @return mixed|array
     */
    public function getConfig($key = false)
    {
        return (!$key) ? $this->config->getAll() : $this->config->get($key);
    }

    /**
     * Create and return a new {@see RequestInterface} configured for the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param string $httpMethod HTTP Method to set on the request.
     * @param array|Collection $headers (optional) Headers to set on the request
     * @param string|resource|EntityBody (optional) Body to set on the request
     *
     * @return RequestInterface
     */
    public function getRequest($httpMethod, $headers = null, $body = null)
    {
        $request = $this->requestFactory->newRequest($httpMethod, $this->getBaseUrl(), $headers, $body);

        // Add your application name to the request
        if ($this->userApplication) {
            $request->setHeader('User-Agent', trim($this->userApplication . ' ' . $request->getHeader('User-Agent')));
        }

        // Add any curl options that might in the config to the request
        foreach ($this->config->getAll(array('/^curl\..+/')) as $key => $value) {
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

        // Attach registered plugins to the request
        foreach ($this->plugins as $plugin) {
            $plugin->attach($request);
        }

        $this->getEventManager()->notify('request.create', $request);

        return $request;
    }

    /**
     * Set the request factory used to create new requests
     *
     * @param RequestFactory $factory Factory used to create new requests
     *
     * @return Client
     */
    public function setRequestFactory(RequestFactory $factory)
    {
        $this->requestFactory = $factory;
        $this->getEventManager()->notify('request.factory.set', $factory);

        return $this;
    }

    /**
     * Attach a plugin to the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param AbstractPlugin $plugin  Plugin to attach to the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @return Client
     */
    public function attachPlugin(AbstractPlugin $plugin)
    {
        if (!$this->hasPlugin(get_class($plugin))) {
            $this->plugins[self::getShortPluginName($plugin)] = $plugin;
            $this->getEventManager()->attach($plugin);
        }

        return $this;
    }

    /**
     * Remove a plugin by plugin or plugin class
     *
     * @param string|AbstractPlugin $plugin The plugin to detach from the
     *      /* Replaced /* Replaced /* Replaced client */ */ */.  Pass a string to remove all plugins that are an instance
     *      of $plugin.  Pass a concrete plugin to remove a specific plugin
     *
     * @return Client
     */
    public function detachPlugin($plugin)
    {
        $foundPlugin = false;
        if (is_string($plugin)) {
            $plugin = self::getShortPluginName($plugin);
        }

        $mediator = $this->getEventManager();
        $that = $this;
        $c = __CLASS__;
        $this->plugins = array_filter($this->plugins, function($p) use ($plugin, $mediator, $that, $c) {
            $short = $c::getShortPluginName($p);
            if ((is_string($plugin) && !strcmp($short, $plugin)) || $p === $plugin) {
                if (!is_string($plugin)) {
                    $mediator->detach($plugin);
                }
                return false;
            }

            return true;
        });

        return $this;
    }

    /**
     * Check if the /* Replaced /* Replaced /* Replaced client */ */ */ has a specific plugin
     *
     * @param string|AbstractPlugin $plugin Check for the existence of a plugin
     *      by class or a concrete plugin
     *
     * @return bool
     */
    public function hasPlugin($plugin)
    {
        if (is_string($plugin)) {
            $plugin = self::getShortPluginName($plugin);
        }

        foreach ($this->plugins as $pluginItem) {
            $short = self::getShortPluginName($pluginItem);
            if ((is_string($plugin) && $short == $plugin) || $pluginItem === $plugin) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get an attached plugin by name
     *
     * @param string $pluginClass Plugin class to retrieve
     *
     * @return AbstractPlugin|bool
     */
    public function getPlugin($pluginClass)
    {
        $short = self::getShortPluginName($pluginClass);

        return (array_key_exists($short, $this->plugins)) ? $this->plugins[$short] : false;
    }

    /**
     * Get a command using the /* Replaced /* Replaced /* Replaced client */ */ */'s CommandFactoryInterface
     *
     * @param string $name Name of the command to retrieve
     * @param array $args (optional) Arguments to pass to the command
     *
     * @return CommandInterface
     */
    public function getCommand($name, array $args = array())
    {
        $command = $this->commandFactory->buildCommand($name, $args);
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
     *
     * @throws InvalidArgumentException if neither a Command or CommandSet is passed
     * @throws Command\CommandSetException if the set contains commands associated
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
            throw new ServiceException('Invalid command sent to ' . __METHOD__);
        }
    }

    /**
     * Get the base service endpoint URL with configuration options injected
     * into the configuration setting.
     *
     * @param bool $inject (optional) Set to FALSE to get the raw base_url
     *
     * @return string
     */
    public function getBaseUrl($inject = true)
    {
        return ($inject) ? $this->injectConfig($this->config->get('base_url')) : $this->config->get('base_url');
    }

    /**
     * Set the base service endpoint URL
     *
     * @param string $url The base service endpoint URL of the webservice
     *
     * @return Client
     */
    public function setBaseUrl($url)
    {
        $this->config->set('base_url', $url);

        return $this;
    }

    /**
     * Inject configuration values into a formatted string with {{ param }} as a
     * parameter delimiter (replace param with the configuration value name)
     *
     * @param string $string String to inject config values into
     *
     * @return string
     */
    public function injectConfig($string)
    {
        return Injector::inject($string, $this->config);
    }

    /**
     * Get the service description of the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @return ServiceDescription
     */
    public function getService()
    {
        return $this->serviceDescription;
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
    public function setUserApplication($appName, $version)
    {
        $this->userApplication = $appName . '/' . ($version ?: '1.0');

        return $this;
    }

    /**
     * Hook method to initialize the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @return void
     */
    protected function init()
    {
        return;
    }
}