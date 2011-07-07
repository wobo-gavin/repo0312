<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Mock;

/**
 * A mock filter class testing that filters are being called correctly in a
 * {@see /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\Chain}
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class MockFilter extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\AbstractFilter
{
    public $called = false;
    public $calls = array();
    public $commands = array();

    public function __call($method, $args)
    {
        $this->calls[] = array(
            'method' => $method,
            'args' => $args
        );
    }

    protected function filterCommand($command)
    {
        $this->commands[] = $command;

        if ($this->get('callback')) {
         
            $func = $this->get('callback');
            return $func($this, $command);
            
        } else {

            $this->called = true;

            if (!is_scalar($command)) {
                $command->value = 'modified';
            }

            return true;
        }
    }
}