<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Curl;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\TransactionInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\HeadersEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\HasHeadersTrait;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\MessageFactoryInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\Stream;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\StreamInterface;

/**
 * Mediator between curl handles and request objects
 */
class RequestMediator
{
    use HasHeadersTrait;

    /** @var TransactionInterface */
    private $transaction;
    /** @var MessageFactoryInterface */
    private $messageFactory;
    private $statusCode;
    private $reasonPhrase;
    private $body;
    private $protocolVersion;

    /**
     * @param TransactionInterface    $transaction    Transaction to populate
     * @param MessageFactoryInterface $messageFactory Message factory used to create responses
     */
    public function __construct(
        TransactionInterface $transaction,
        MessageFactoryInterface $messageFactory
    ) {
        $this->transaction = $transaction;
        $this->messageFactory = $messageFactory;
    }

    /**
     * Set the body that will hold the response body
     *
     * @param StreamInterface $body Response body
     */
    public function setResponseBody(StreamInterface $body = null)
    {
        $this->body = $body;
    }

    /**
     * Receive a response header from curl
     *
     * @param resource $curl   Curl handle
     * @param string   $header Received header
     *
     * @return int
     */
    public function receiveResponseHeader($curl, $header)
    {
        static $normalize = ["\r", "\n"];
        $length = strlen($header);
        $header = str_replace($normalize, '', $header);

        if (substr($header, 0, 5) == 'HTTP/') {
            $startLine = explode(' ', $header, 3);
            // Only download the body to a target body when a successful
            // response is received.
            if ($startLine[1][0] != '2') {
                $this->body = null;
            }
            $this->statusCode = $startLine[1];
            $this->reasonPhrase = isset($startLine[2]) ? $startLine[2] : null;
            $this->protocolVersion = substr($startLine[0], -3);
        } elseif ($pos = strpos($header, ':')) {
            $this->addHeader(substr($header, 0, $pos), substr($header, $pos + 1));
        } elseif ($header == '' && $this->statusCode >= 200) {
            $response = $this->messageFactory->createResponse(
                $this->statusCode,
                $this->getHeaders(),
                $this->body,
                [
                    'protocol_version' => $this->protocolVersion,
                    'reason_phrase'    => $this->reasonPhrase
                ]
            );
            $this->headers = $this->body = null;
            $this->transaction->setResponse($response);
            // Allows events to react before downloading any of the body
            $this->transaction->getRequest()->getEmitter()->emit(
                'headers',
                new HeadersEvent($this->transaction)
            );
        }

        return $length;
    }

    /**
     * Write data to the response body of a request
     *
     * @param resource $curl
     * @param string   $write
     *
     * @return int
     */
    public function writeResponseBody($curl, $write)
    {
        if (!($response = $this->transaction->getResponse())) {
            return 0;
        }

        // Add a default body on the response if one was not found
        if (!($body = $response->getBody())) {
            $body = new Stream(fopen('php://temp', 'r+'));
            $response->setBody($body);
        }

        return $body->write($write);
    }

    /**
     * Read data from the request body and send it to curl
     *
     * @param resource $ch     Curl handle
     * @param resource $fd     File descriptor
     * @param int      $length Amount of data to read
     *
     * @return string
     */
    public function readRequestBody($ch, $fd, $length)
    {
        return (string) $this->transaction->getRequest()->getBody()->read($length);
    }
}
