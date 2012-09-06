<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Validation;

/**
 * Ensures that a value is blank
 */
class Blank implements ConstraintInterface
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, array $options = null)
    {
        if ($value !== '' && $value !== null) {
            return 'Value must be blank';
        }

        return true;
    }
}
