<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Log;

/**
 * Adapter class that allows /* Replaced /* Replaced /* Replaced Guzzle */ */ */ to log dato to various logging
 * implementations so that you may use the log classes of your favorite
 * framework.
 * 
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
abstract class AbstractLogAdapter implements LogAdapterInterface
{
    /**
     * @var mixed Concrete wrapped log object
     */
    protected $log;

    /**
     * Get the wrapped log object
     *
     * @return mixed
     */
    public function getLogObject()
    {
        return $this->log;
    }
}