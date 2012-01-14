<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command;

/**
 * Mock Command
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ test default="123" required="true" doc="Test argument"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ other
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ _internal default="abc"
 */
class MockCommand extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->createRequest();
    }
}