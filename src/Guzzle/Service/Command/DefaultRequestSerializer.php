<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Url;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Parser\ParserRegistry;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Request\RequestVisitorInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Request\BodyVisitor;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Request\HeaderVisitor;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Request\JsonVisitor;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Request\QueryVisitor;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Request\PostFieldVisitor;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Request\PostFileVisitor;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Request\XmlVisitor;

/**
 * Default request serializer that transforms command options and operation parameters into a request
 */
class DefaultRequestSerializer implements RequestSerializerInterface
{
    /**
     * @var array Location visitors attached to the command
     */
    protected $visitors = array();

    /**
     * @var array Cached instance with default visitors
     */
    protected static $instance;

    /**
     * Get a default instance that includes that default location visitors
     *
     * @return self
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self(array(
                'header'    => new HeaderVisitor(),
                'query'     => new QueryVisitor(),
                'body'      => new BodyVisitor(),
                'json'      => new JsonVisitor(),
                'postFile'  => new PostFileVisitor(),
                'postField' => new PostFieldVisitor(),
                'xml'       => new XmlVisitor(),
            ));
        }

        return self::$instance;
    }

    /**
     * @param array $visitors Visitors to attache
     */
    public function __construct(array $visitors = array())
    {
        $this->visitors = $visitors;
    }

    /**
     * Add a location visitor to the command
     *
     * @param string                   $location Location to associate with the visitor
     * @param RequestVisitorInterface  $visitor  Visitor to attach
     *
     * @return self
     */
    public function addVisitor($location, RequestVisitorInterface $visitor)
    {
        $this->visitors[$location] = $visitor;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare(CommandInterface $command)
    {
        $operation = $command->getOperation();
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $command->getClient();
        $uri = $operation->getUri();

        if (!$uri) {
            $url = $/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl();
        } else {
            // Get the path values and use the /* Replaced /* Replaced /* Replaced client */ */ */ config settings
            $variables = $/* Replaced /* Replaced /* Replaced client */ */ */->getConfig()->getAll();
            foreach ($operation->getParams() as $name => $arg) {
                if ($arg->getLocation() == 'uri' && $command->hasKey($name)) {
                    $variables[$name] = (string) $command->get($name);
                }
            }
            // Merge the /* Replaced /* Replaced /* Replaced client */ */ */'s base URL with an expanded URI template
            $url = (string) Url::factory($/* Replaced /* Replaced /* Replaced client */ */ */->getBaseUrl())
                ->combine(ParserRegistry::getInstance()->getParser('uri_template')->expand($uri, $variables));
        }

        // Inject path and base_url values into the URL
        $request = $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest($operation->getHttpMethod(), $url);

        // Add arguments to the request using the location attribute
        foreach ($operation->getParams() as $name => $arg) {
            /** @var $arg \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Parameter */
            $location = $arg->getLocation();
            // Visit with the associated visitor
            if (isset($this->visitors[$location])) {
                // Ensure that a value has been set for this parameter
                $value = $command->get($name);
                if ($value !== null) {
                    // Apply the parameter value with the location visitor
                    $this->visitors[$location]->visit($command, $request, $arg, $value);
                }
            }
        }

        // Call the after method on each visitor
        foreach ($this->visitors as $visitor) {
            $visitor->after($command, $request);
        }

        return $request;
    }
}
