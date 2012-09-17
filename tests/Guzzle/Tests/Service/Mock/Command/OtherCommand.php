<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Operation;

class OtherCommand extends MockCommand
{
    protected function createOperation()
    {
        return new Operation(array(
            'name'       => 'other_command',
            'parameters' => array(
                'test' => array(
                    'default'  => '123',
                    'required' => true,
                    'doc'      => 'Test argument'
                ),
                'other'  => array(),
                'arg'    => array('type' => 'string'),
                'static' => array('static' => true, 'default' => 'this is static')
            )
        ));
    }

    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getRequest('HEAD');
    }
}
