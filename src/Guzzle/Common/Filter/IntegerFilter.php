<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter;

/**
 * Check if the supplied variable is an Integer
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class IntegerFilter extends AbstractFilter
{
    /**
     * {@inheritdoc}
     */
    protected function filterCommand($command)
    {
        if (!is_numeric($command) || strpos($command, '.') !== false) {

            return 'The supplied value is not a valid integer: '
                . (string) $command . ' supplied';
        }

        return true;
    }
}