<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Parameter;

/**
 * Response parser that will not walk a model structure, but does implement native parsing and creating model objects
 * @codeCoverageIgnore
 */
class NoTranslationOperationResponseParser extends OperationResponseParser
{
    /**
     * {@inheritdoc}
     */
    protected static $instance;

    /**
     * {@inheritdoc}
     */
    protected function visitResult(
        Parameter $model,
        CommandInterface $command,
        Response $response,
        array &$result,
        $context = null
    ) {}
}
