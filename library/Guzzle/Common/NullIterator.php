<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common;

/**
 * Implements the NULL Object design pattern for iterators.
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class NullIterator extends NullObject implements \Iterator, \Countable
{
    public function current()
    {
        return null;
    }

    public function key()
    {
        return null;
    }

    public function next()
    {
        return null;
    }

    public function rewind()
    {
        return null;
    }

    public function valid()
    {
        return null;
    }

    public function count()
    {
        return null;
    }
}