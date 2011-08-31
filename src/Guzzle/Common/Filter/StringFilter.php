<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter;

/**
 * Check if the supplied variable is a string
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class StringFilter extends AbstractFilter
{
    /**
     * {@inheritdoc}
     */
    protected function filterCommand($command)
    {
        if (!is_string($command)) {

            return 'The supplied value is not a string: ' . gettype($command)
                . ' supplied';
        }

        return true;
    }
}