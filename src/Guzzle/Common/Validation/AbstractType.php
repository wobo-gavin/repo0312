<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Validation;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException;

/**
 * Ensures that a value is of a specific type
 */
abstract class  AbstractType extends AbstractConstraint
{
    protected static $typeMapping = array();
    protected $default = 'type';
    protected $required = 'type';

    /**
     * {@inheritdoc}
     */
    protected function validateValue($value, array $options = array())
    {
        $type = (string) $options['type'];

        if (!isset(static::$typeMapping[$type])) {
            throw new InvalidArgumentException("{$type} is not one "
                . 'the mapped types: ' . array_keys(self::$typeMapping));
        }

        $method = static::$typeMapping[$type];
        if (!call_user_func($method, $value)) {
            return 'Value must be of type ' . $type;
        }

        return true;
    }
}
