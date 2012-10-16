<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Operation;

class MockCommand extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand
{
    protected function createOperation()
    {
        return new Operation(array(
            'name'       => get_called_class() == __CLASS__ ? 'mock_command' : 'sub.sub',
            'httpMethod' => 'POST',
            'parameters' => array(
                'test' => array(
                    'default'  => 123,
                    'required' => true,
                    'doc'      => 'Test argument'
                ),
                '_internal' => array(
                    'default' => 'abc'
                ),
                'foo' => array('filters' => array('strtoupper'))
            )
        ));
    }

    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->createRequest();
    }
}
