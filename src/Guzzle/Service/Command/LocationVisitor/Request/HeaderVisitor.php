<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor\Request;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Parameter;

/**
 * Visitor used to apply a parameter to a header value
 */
class HeaderVisitor extends AbstractRequestVisitor
{
    /**
     * {@inheritdoc}
     */
    public function visit(CommandInterface $command, RequestInterface $request, Parameter $param, $value)
    {
        if ($param->getType() == 'object' && $param->getAdditionalProperties() instanceof Parameter) {
            if (!is_array($value)) {
                throw new InvalidArgumentException('An array of mapped headers expected, but received a single value');
            }
            $prefix = $param->getSentAs();
            foreach ($value as $headerName => $headerValue) {
                $request->setHeader($prefix . $headerName, $headerValue);
            }
        } else {
            $request->setHeader($param->getWireName(), $value);
        }
    }
}
