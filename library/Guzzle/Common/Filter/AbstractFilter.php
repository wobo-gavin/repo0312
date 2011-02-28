<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\/* Replaced /* Replaced /* Replaced Guzzle */ */ */Exception;

/**
 * An intercepting filter.
 *
 * @author  michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org
 */
abstract class AbstractFilter extends Collection implements FilterInterface
{
    /**
     * Create a new filter object.
     *
     * @param array|Collection $parameters (optional) Optional parameters to
     *      pass to the filter for processing.
     *
     * @throws /* Replaced /* Replaced /* Replaced Guzzle */ */ */Exception if the $parameters argument is not an array or an
     *      instance of {@see Collection}
     */
    public function __construct($parameters = null)
    {
        if ($parameters instanceof Collection) {
            $this->data = $parameters->getAll();
        } else {
            parent::__construct($parameters);
        }

        $this->init();
    }

    /**
     * Process the command object.
     *
     * @param mixed $command Value to process.  The command can be any type of
     *      variable.  It is the responsibility of concrete filters to ensure
     *      that the passed command is of the correct type.
     *
     * @return bool Returns TRUE on success or FALSE on failure.
     */
    public function process($command)
    {
        $canProcess = true;
        if ($this->hasKey('type_hint')) {
            $typeHint = $this->get('type_hint');
            if (!($command instanceof $typeHint)) {
                $canProcess = false;
            }
        }

        return ($canProcess) ? $this->filterCommand($command) : false;
    }

    /**
     * Filter the request and handle as needed.
     *
     * This method is a hook to be implemented in subclasses
     *
     * @param mixed $command The object to process
     *
     * @return bool Returns TRUE on success or FALSE on failure
     */
    abstract protected function filterCommand($command);

    /**
     * Initialize the filter.
     *
     * This method is a hook to be implemented in subclasses that handles
     * initializing the filter.
     *
     * @return void
     */
    protected function init()
    {
        return;
    }
}