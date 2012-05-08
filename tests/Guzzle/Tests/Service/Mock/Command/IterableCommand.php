<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Service\Mock\Command;

/**
 * Iterable mock command
 *
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ page_size type="integer"
 * @/* Replaced /* Replaced /* Replaced guzzle */ */ */ next_token type="string"
 */
class IterableCommand extends MockCommand
{
    /**
     * {@inheritdoc}
     */
    protected function build()
    {
        $this->request = $this->/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET');

        // Add the next token and page size query string values
        $this->request->getQuery()->set('next_token', $this->get('next_token'));

        if ($this->get('page_size')) {
            $this->request->getQuery()->set('page_size', $this->get('page_size'));
        }
    }
}
