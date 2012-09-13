<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser\ParserRegistry;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\LocationVisitorInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\BodyVisitor;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\HeaderVisitor;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\JsonBodyVisitor;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\QueryVisitor;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\PostFieldVisitor;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\PostFileVisitor;

/**
 * A command that creates requests based on {see ApiCommandInterface} objects
 */
class DynamicCommand extends AbstractCommand
{
    /**
     * @var array Location visitors attached to the command
     */
    protected $visitors = array();

    /**
     * @var array Cached instantiated visitors
     */
    protected static $visitorCache = array();

    /**
     * Add a location visitor to the command
     *
     * @param string                   $location Location to associate with the visitor
     * @param LocationVisitorInterface $visitor  Visitor to attach
     *
     * @return self
     */
    public function addVisitor($location, LocationVisitorInterface $visitor)
    {
        $this->visitors[$location] = $visitor;

        return $this;
    }

    /**
     * Initialize the command by adding the default visitors.
     *
     * Override this method if you want to remove default visitor locations, add custom locations, or change visitors
     * associated with specific locations.
     */
    protected function init()
    {
        if (!self::$visitorCache) {
            self::$visitorCache = array(
                'header'     => new HeaderVisitor(),
                'query'      => new QueryVisitor(),
                'body'       => new BodyVisitor(),
                'json'       => new JsonBodyVisitor(),
                'post_file'  => new PostFileVisitor(),
                'post_field' => new PostFieldVisitor()
            );
        }

        $this->visitors = self::$visitorCache;
    }

    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $uri = $this->apiCommand->getUri();

        if (!$uri) {
            $url = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl();
        } else {
            // Get the path values and use the /* Replaced /* Replaced /* Replaced client */ */ */ config settings
            $variables = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getConfig()->getAll();
            foreach ($this->apiCommand->getParams() as $name => $arg) {
                $configValue = $this->get($name);
                if (is_scalar($configValue)) {
                    $variables[$name] = $arg->getPrepend() . $configValue . $arg->getAppend();
                }
            }
            // Merge the /* Replaced /* Replaced /* Replaced client */ */ */'s base URL with an expanded URI template
            $url = (string) Url::factory($this->/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl())
                ->combine(ParserRegistry::get('uri_template')->expand($uri, $variables));
        }

        // Inject path and base_url values into the URL
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->createRequest($this->apiCommand->getMethod(), $url);

        // Add arguments to the request using the location attribute
        foreach ($this->apiCommand->getParams() as $name => $arg) {
            $location = $arg->getLocation();
            // Visit with the associated visitor
            if (isset($this->visitors[$location])) {
                // Ensure that a value has been set for this parameter
                $configValue = $this->get($name);
                if ($configValue !== null) {
                    // Create the value based on prepend and append settings
                    if ($arg->getPrepend() || $arg->getAppend()) {
                        $value = $arg->getPrepend() . $configValue . $arg->getAppend();
                    } else {
                        $value = $configValue;
                    }
                    // Apply the parameter value with the location visitor
                    $this->visitors[$location]->visit($this, $this->request, $arg->getLocationKey(), $value, $arg);
                }
            }
        }

        // Call the after method on each visitor
        foreach ($this->visitors as $visitor) {
            $visitor->after($this, $this->request);
        }
    }
}
