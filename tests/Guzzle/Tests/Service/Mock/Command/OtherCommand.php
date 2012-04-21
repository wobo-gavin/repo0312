<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command;

/**
 * Other mock Command
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ test default="123" required="true" doc="Test argument"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ other
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ arg type="string
 * /* Replaced /* Replaced /* Replaced guzzle */ */ */ static static="this is static"
 */
class OtherCommand extends MockCommand
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getRequest('HEAD');
    }
}
