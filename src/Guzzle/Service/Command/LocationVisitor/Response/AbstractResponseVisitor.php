<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Response;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Parameter;

/**
 * {@inheritdoc}
 */
abstract class AbstractResponseVisitor implements ResponseVisitorInterface
{
    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function after(CommandInterface $command) {}

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function visit(CommandInterface $command, Response $response, Parameter $param, &$value, $context =  null) {}
}
