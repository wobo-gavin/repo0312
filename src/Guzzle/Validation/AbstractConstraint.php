<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Validation;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException;

/**
 * Abstract constraint class
 */
abstract class AbstractConstraint implements ConstraintInterface
{
    protected static $defaultOption = null;
    protected $required;

    /**
     * {@inheritdoc}
     */
    public function validate($value, array $options = null)
    {
        // Always pass an array to the hook method
        if (!$options) {
            $options = array();
        } elseif (static::$defaultOption && isset($options[0])) {
            // Add the default configuration option if an enumerated array
            // is passed
            $options[static::$defaultOption] = $options[0];
        }

        // Ensure that required options are present
        if ($this->required && !isset($options[$this->required])) {
            throw new InvalidArgumentException("{$this->required} is a required validation option");
        }

        return $this->validateValue($value, $options);
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public static function getDefaultOption()
    {
        return static::$defaultOption;
    }

    /**
     * Perform the actual validation in a concrete class
     *
     * @param mixed $value   Value to validate
     * @param array $options Validation options
     *
     * @return bool|string
     */
    abstract protected function validateValue($value, array $options = array());
}
