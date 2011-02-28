<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter;

/**
 * An implementation of an intercepting filter using a closure as the processing
 * method.
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */-project.org>
 */
class ClosureFilter extends AbstractFilter
{
    /**
     * @var Closure closure method used to process the command
     */
    private $closure;

    /**
     * Create a new closure filter
     */
    public function __construct(\Closure $closure)
    {
        parent::__construct();
        $this->closure = $closure;
    }

    /**
     * {@inheritdoc}
     */
    protected function filterCommand($command)
    {
        return call_user_func($this->closure, $command);
    }
}