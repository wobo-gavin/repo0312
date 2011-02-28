<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter;

/**
 * Check if the supplied variable is a Date
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class DateFilter extends AbstractFilter
{
    /**
     * {@inheritdoc}
     */
    protected function filterCommand($command)
    {
        if (!is_scalar($command)) {

            return 'The supplied value is not a valid date: '
                . gettype($command) . ' supplied';

        } else {

            $s = strtotime($command);

            if (false === $s
                || !checkdate(date('m', $s), date('d', $s), date('Y', $s))) {

                return 'The supplied value is not a valid date: '
                    . (string) $command . ' supplied';
            }
        }

        return true;
    }
}