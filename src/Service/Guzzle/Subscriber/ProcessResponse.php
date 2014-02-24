<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Subscriber;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\SubscriberInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\ResponseInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Description\Parameter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\ResponseLocation\JsonLocation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\ProcessEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\ResponseLocation\ResponseLocationInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\ResponseLocation\BodyLocation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\ResponseLocation\StatusCodeLocation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\ResponseLocation\ReasonPhraseLocation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\ResponseLocation\HeaderLocation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\ResponseLocation\XmlLocation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\Model;

/**
 * Subscriber used to create response models based on an HTTP response and
 * a service description.
 *
 * Response location visitors are registered with this subscriber to handle
 * locations (e.g., 'xml', 'json', 'header'). All of the locations of a response
 * model that will be visited first have their ``before`` method triggered.
 * After the before method is called on every visitor that will be walked, each
 * visitor is triggered using the ``visit()`` method. After all of the visitors
 * are visited, the ``after()`` method is called on each visitor. This is the
 * place in which you should handle things like additionalProperties with
 * custom locations (i.e., this is how it is handled in the JSON visitor).
 */
class ProcessResponse implements SubscriberInterface
{
    /** @var ResponseLocationInterface[] */
    private $responseLocations;

    public static function getSubscribedEvents()
    {
        return ['process' => ['onProcess']];
    }

    /**
     * @param ResponseLocationInterface[] $responseLocations Extra response locations
     */
    public function __construct(array $responseLocations = [])
    {
        static $defaultResponseLocations;
        if (!$defaultResponseLocations) {
            $defaultResponseLocations = [
                'body'         => new BodyLocation('body'),
                'header'       => new HeaderLocation('header'),
                'reasonPhrase' => new ReasonPhraseLocation('reasonPhrase'),
                'statusCode'   => new StatusCodeLocation('statusCode'),
                'xml'          => new XmlLocation('xml'),
                'json'         => new JsonLocation('json')
            ];
        }

        $this->responseLocations = $responseLocations + $defaultResponseLocations;
    }

    public function onProcess(ProcessEvent $event)
    {
        $command = $event->getCommand();
        if (!($command instanceof /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface)) {
            throw new \RuntimeException('Invalid command');
        }

        $operation = $command->getOperation();
        $type = $operation->getResponseType();

        if ($type == 'class') {
            $event->setResult($this->createClass($event));
        } elseif ($type == 'primitive') {
            return;
        }

        $model = $operation->getServiceDescription()->getModel($operation->getResponseClass());

        if (!$model) {
            throw new \RuntimeException('No model found matching: '
                . $operation->getResponseClass());
        }

        $event->setResult(new Model($this->visit($model, $event)));
    }

    protected function createClass(ProcessEvent $event)
    {
        return null;
    }

    protected function visit(Parameter $model, ProcessEvent $event)
    {
        $result = [];
        $context = ['/* Replaced /* Replaced /* Replaced client */ */ */' => $event->getClient(), 'visitors' => []];
        $command = $event->getCommand();
        $response = $event->getResponse();

        if ($model->getType() == 'object') {
            $this->visitOuterObject($model, $result, $command, $response, $context);
        } elseif ($model->getType() == 'array') {
            $this->visitOuterArray($model, $result, $command, $response, $context);
        } else {
            throw new \InvalidArgumentException('Invalid response model: ' . $model->getType());
        }

        // Call the after() method of each found visitor
        foreach ($context['visitors'] as $visitor) {
            $visitor->after($command, $response, $model, $result, $context);
        }

        return $result;
    }

    private function triggerBeforeVisitor(
        $location,
        Parameter $model,
        array &$result,
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface $command,
        ResponseInterface $response,
        array &$context
    ) {
        if (!isset($this->responseLocations[$location])) {
            throw new \RuntimeException("Unknown location: $location");
        }

        $context['visitors'][$location] = $this->responseLocations[$location];

        $this->responseLocations[$location]->before(
            $command,
            $response,
            $model,
            $result,
            $context
        );
    }

    private function visitOuterObject(
        Parameter $model,
        array &$result,
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface $command,
        ResponseInterface $response,
        array &$context
    ) {
        // If top-level additionalProperties is a schema, then visit it
        $additional = $model->getAdditionalProperties();
        if ($additional instanceof Parameter) {
            $this->triggerBeforeVisitor($additional->getLocation(), $model,
                $result, $command, $response, $context);
        }

        // Use 'location' from all individual defined properties
        $properties = $model->getProperties();
        foreach ($properties as $schema) {
            if ($location = $schema->getLocation()) {
                // Trigger the before method on each unique visitor location
                if (!isset($context['visitors'][$location])) {
                    $this->triggerBeforeVisitor($location, $model, $result,
                        $command, $response, $context);
                }
            }
        }

        // Actually visit each response element
        foreach ($properties as $schema) {
            if ($location = $schema->getLocation()) {
                $this->responseLocations[$location]->visit($command, $response,
                    $schema, $result, $context);
            }
        }
    }

    private function visitOuterArray(
        Parameter $model,
        array &$result,
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface $command,
        ResponseInterface $response,
        array &$context
    ) {
        // Use 'location' defined on the top of the model
        if (!($location = $model->getLocation())) {
            return;
        }

        if (!isset($foundVisitors[$location])) {
            $this->triggerBeforeVisitor($location, $model, $result,
                $command, $response, $context);
        }

        // Visit each item in the response
        $this->responseLocations[$location]->visit($command, $response,
            $model, $result, $context);
    }
}
