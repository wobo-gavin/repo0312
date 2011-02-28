<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\Filter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\AbstractFilter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log\LogException;

/**
 * Filters log messages based on the message priority
 *
 * This filter must have a 'priority' value passed to its constructor.  The
 * priority value is the minimum priority in which the Adapter will log
 * messages.
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class PriorityFilter extends AbstractFilter
{
    /**
     * {@inheritdoc}
     *
     * @throws LogFilterException
     */
    protected function init()
    {
        if (!$this->get('priority')) {
            throw new LogException(
                'A priority value must be specified on a priority log filter'
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function filterCommand($command)
    {
        return $command['priority'] <= $this->get('priority');
    }
}