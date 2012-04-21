<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\UnexpectedValueException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;

/**
 * A ClosureCommand is a command that allows dynamic commands to be created at
 * runtime using a closure to prepare the request.  A closure key and \Closure
 * value must be passed to the command in the constructor.  The closure must
 * accept the command object as an argument.
 */
class ClosureCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException if a closure was not passed
     */
    protected function init()
    {
        if (!$this->get('closure')) {
            throw new InvalidArgumentException('A closure must be passed in the parameters array');
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws UnexpectedValueException If the closure does not return a request
     */
    protected function build()
    {
        $closure = $this->get('closure');
        $this->request = $closure($this, $this->apiCommand);

        if (!$this->request || !$this->request instanceof RequestInterface) {
            throw new UnexpectedValueException('Closure command did not return a RequestInterface object');
        }
    }
}
