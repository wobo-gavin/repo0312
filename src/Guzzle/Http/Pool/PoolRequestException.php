<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Pool;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\HttpException;

/**
 * Pool exception that serves as a container for for any
 * {@see /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface} request exceptions encountered
 * during the transfer of a Pool.  This allows the failure of a single request
 * to be isolated so that other requests in the same pool can still successfully
 * complete.
 *
 * @author  michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org
 */
class PoolRequestException extends HttpException implements \IteratorAggregate, \Countable
{
    /**
     * @var array Array of RequestException objects
     */
    protected $exceptions = array();

    /**
     * Set the request that caused the exception
     *
     * @param RequestInterface $request Request to set
     */
    public function addException(RequestException $request)
    {
        $this->exceptions[] = $request;
    }

    /**
     * Get the exceptions thrown during the Pool transfer
     *
     * @return array Returns an array of RequestException objects
     */
    public function getRequestExceptions()
    {
        return $this->exceptions;
    }

    /**
     * Get the total number of request exceptions
     *
     * @return int
     */
    public function count()
    {
        return count($this->exceptions);
    }

    /**
     * Allows array-like iteration over the request exceptions
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->exceptions);
    }
}