<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Response;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Parameter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ArrayCommandInterface;

/**
 * Location visitor used to add the reason phrase of a response to a key in the result
 */
class ReasonPhraseVisitor extends AbstractResponseVisitor
{
    public function visit(
        ArrayCommandInterface $command,
        Response $response,
        Parameter $param,
        &$value,
        $context =  null
    ) {
        $value[$param->getName()] = $response->getReasonPhrase();
    }
}
