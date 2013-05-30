<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Response;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Parameter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ArrayCommandInterface;

/**
 * Location visitor used to marshal JSON response data into a formatted array.
 *
 * Allows top level JSON parameters to be inserted into the result of a command. The top level attributes are grabbed
 * from the response's JSON data using the name value by default. Filters can be applied to parameters as they are
 * traversed. This allows data to be normalized before returning it to users (for example converting timestamps to
 * DateTime objects).
 */
class JsonVisitor extends AbstractResponseVisitor
{
    public function before(ArrayCommandInterface $command, array &$result)
    {
        // Ensure that the result of the command is always rooted with the parsed JSON data
        $result = $command->getResponse()->json();
    }

    public function visit(
        ArrayCommandInterface $command,
        Response $response,
        Parameter $param,
        &$value,
        $context =  null
    ) {
        $name = $param->getName();
        $key = $param->getWireName();
        if (isset($value[$key])) {
            $this->recursiveProcess($param, $value[$key]);
            if ($key != $name) {
                $value[$name] = $value[$key];
                unset($value[$key]);
            }
        }
    }

    /**
     * Recursively process a parameter while applying filters
     *
     * @param Parameter $param API parameter being validated
     * @param mixed     $value Value to validate and process. The value may change during this process.
     */
    protected function recursiveProcess(Parameter $param, &$value)
    {
        if ($value === null) {
            return;
        }

        if (is_array($value)) {
            $type = $param->getType();
            if ($type == 'array') {
                foreach ($value as &$item) {
                    $this->recursiveProcess($param->getItems(), $item);
                }
            } elseif ($type == 'object' && !isset($value[0])) {
                // On the above line, we ensure that the array is associative and not numerically indexed
                if ($properties = $param->getProperties()) {
                    foreach ($properties as $property) {
                        $name = $property->getName();
                        $key = $property->getWireName();
                        if (isset($value[$key])) {
                            $this->recursiveProcess($property, $value[$key]);
                            if ($key != $name) {
                                $value[$name] = $value[$key];
                                unset($value[$key]);
                            }
                        }
                    }
                }
            }
        }

        $value = $param->filter($value);
    }
}
