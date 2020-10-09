<?php

namespace /* Replaced /* Replaced Guzzle */ */Http;

use Psr\Http\Message\MessageInterface;

final class BodySummarizer implements BodySummarizerInterface
{
    /**
     * @var int|null
     */
    private $truncateAt;

    public function __construct(int $truncateAt = null)
    {
        $this->truncateAt = $truncateAt;
    }

    /**
     * Returns a summarized message body.
     */
    public function summarize(MessageInterface $message): ?string
    {
        return $this->truncateAt === null
            ? \/* Replaced /* Replaced Guzzle */ */Http\/* Replaced /* Replaced Psr7 */ */\Message::bodySummary($message)
            : \/* Replaced /* Replaced Guzzle */ */Http\/* Replaced /* Replaced Psr7 */ */\Message::bodySummary($message, $this->truncateAt);
    }
}
