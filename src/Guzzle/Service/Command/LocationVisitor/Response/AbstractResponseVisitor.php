<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Response;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Parameter;

/**
 * {@inheritdoc}
 * @codeCoverageIgnore
 */
abstract class AbstractResponseVisitor implements ResponseVisitorInterface
{
    public function before(CommandInterface $command, array &$result) {}

    public function after(CommandInterface $command) {}

    public function visit(
        CommandInterface $command,
        Response $response,
        Parameter $param,
        &$value,
        $context =  null
    ) {}
}
