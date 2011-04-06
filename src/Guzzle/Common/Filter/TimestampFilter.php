<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter;

/**
 * Check if the supplied variable is a timestamp
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class TimestampFilter extends AbstractFilter
{
    /**
     * {@inheritdoc}
     */
    protected function filterCommand($command)
    {
        if (!is_numeric($command) || false === date('Y-m-d', (float) $command)) {
            if (is_scalar($command)) {

                return 'The supplied value is not a valid timestamp: ' 
                    . (string) $command . ' supplied';
            } else {

                return 'The supplied value is not a valid timestamp: '
                    . gettype($command) . ' supplied';
            }
        }

        return true;
    }
}