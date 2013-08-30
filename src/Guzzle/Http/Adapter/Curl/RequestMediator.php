<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Curl;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestEvents;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\MessageFactoryInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\Stream;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamInterface;

/**
 * Mediator between curl handles and request objects
 */
class RequestMediator
{
    /** @var Transaction */
    private $transaction;
    /** @var MessageFactoryInterface */
    private $messageFactory;
    private $statusCode;
    private $reasonPhrase;
    private $headers = [];
    private $body;
    private $protocolVersion;

    /**
     * @param Transaction             $transaction    Transaction to populate
     * @param MessageFactoryInterface $messageFactory Message factory used to create responses
     */
    public function __construct(Transaction $transaction, MessageFactoryInterface $messageFactory)
    {
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
        static $normalize = array("\r", "\n");
        $length = strlen($header);
        $header = str_replace($normalize, '', $header);

        if (substr($header, 0, 5) == 'HTTP/') {
            $startLine = explode(' ', $header, 3);
            // Only download the body to a target body when a successful response is received
            if ($startLine[1][0] != '2') {
                $this->body = null;
            }
            $this->statusCode = $startLine[1];
            $this->reasonPhrase = isset($startLine[2]) ? $startLine[2] : null;
            $this->protocolVersion = substr($startLine[0], -3);
        } elseif ($pos = strpos($header, ':')) {
            $this->headers[substr($header, 0, $pos)] = substr($header, $pos + 1);
        } elseif ($header == '' && $this->statusCode >= 200) {
            $response = $this->messageFactory->createResponse(
                $this->statusCode,
                $this->headers,
                $this->body,
                ['protocol_version' => $this->protocolVersion, 'reason_phrase' => $this->reasonPhrase]
            );
            $this->headers = $this->body = null;
            $this->transaction->setResponse($response);
            $request = $this->transaction->getRequest();
            // Allows events to react before downloading any of the body
            $request->dispatch(RequestEvents::GOT_HEADERS, ['request' => $request, 'response' => $response]);
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
        if ($response = $this->transaction->getResponse()) {
            return $response->getBody()->write($write);
        }
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
        return (string) $this->request->getBody()->read($length);
    }
}
