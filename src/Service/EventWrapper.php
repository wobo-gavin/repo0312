<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ErrorEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\HasEmitterTrait;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\ResponseInterface;

/**
 * Utility class used to wrap HTTP events with /* Replaced /* Replaced /* Replaced client */ */ */ events.
 */
class EventWrapper
{
    /**
     * Handles the workflow of a command before it is sent.
     *
     * This includes preparing a request for the command, hooking the command
     * event system up to the request's event system, and returning the
     * prepared request.
     *
     * @param CommandInterface       $command Command to prepare
     * @param ServiceClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */  Client that executes the command
     *
     * @return PrepareEvent returns the PrepareEvent. You can use this to see
     *     if the event was intercepted with a result, or to grab the request
     *     that was prepared for the event.
     *
     * @throws \RuntimeException
     */
    public static function prepareCommand(
        CommandInterface $command,
        ServiceClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */
    ) {
        $event = self::prepareEvent($command, $/* Replaced /* Replaced /* Replaced client */ */ */);
        $request = $event->getRequest();

        if ($request) {
            self::injectErrorHandler($command, $/* Replaced /* Replaced /* Replaced client */ */ */, $request);
        } elseif ($event->getResult() === null) {
            throw new \RuntimeException('No request was prepared for the '
                . 'command and no result was added to intercept the event. One '
                . 'of the listeners must set a request on the prepare event.');
        }

        return $event;
    }

    /**
     * Handles the processing workflow of a command after it has been sent and
     * a response has been received.
     *
     * @param CommandInterface       $command  Command that was executed
     * @param ServiceClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */   Client that sent the command
     * @param RequestInterface       $request  Request that was sent
     * @param ResponseInterface      $response Response that was received
     * @param mixed                  $result   Specify the result if available
     *
     * @return mixed|null Returns the result of the command
     */
    public static function processCommand(
        CommandInterface $command,
        ServiceClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */,
        RequestInterface $request,
        ResponseInterface $response = null,
        $result = null
    ) {
        $event = new ProcessEvent($command, $/* Replaced /* Replaced /* Replaced client */ */ */, $request, $response, $result);
        $command->getEmitter()->emit('process', $event);

        return $event->getResult();
    }

    /**
     * Prepares a command for sending and returns the prepare event.
     */
    private static function prepareEvent(
        CommandInterface $command,
        ServiceClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */
    ) {
        try {
            $event = new PrepareEvent($command, $/* Replaced /* Replaced /* Replaced client */ */ */);
            $command->getEmitter()->emit('prepare', $event);
            return $event;
        } catch (CommandException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new CommandException(
                'Error preparing command: ' . $e->getMessage(),
                $/* Replaced /* Replaced /* Replaced client */ */ */,
                $command,
                null,
                null,
                $e
            );
        }
    }

    /**
     * Wrap HTTP level errors with command level errors.
     */
    private static function injectErrorHandler(
        CommandInterface $command,
        ServiceClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */,
        RequestInterface $request
    ) {
        $request->getEmitter()->on(
            'error',
            function (ErrorEvent $e) use ($command, $/* Replaced /* Replaced /* Replaced client */ */ */) {
                $event = new CommandErrorEvent($command, $/* Replaced /* Replaced /* Replaced client */ */ */, $e);
                $command->getEmitter()->emit('error', $event);

                if ($event->getResult() === null) {
                    throw new CommandException(
                        'Error executing command: ' . $e->getException()->getMessage(),
                        $/* Replaced /* Replaced /* Replaced client */ */ */,
                        $command,
                        $e->getRequest(),
                        $e->getResponse(),
                        $e->getException()
                    );
                }

                $e->stopPropagation();
                self::processCommand(
                    $command,
                    $/* Replaced /* Replaced /* Replaced client */ */ */,
                    $event->getRequest(),
                    null,
                    $event->getResult()
                );
            }
        );
    }
}
