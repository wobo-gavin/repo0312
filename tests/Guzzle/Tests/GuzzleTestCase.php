<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Server;

abstract class /* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase extends \PHPUnit_Framework_TestCase
{
    public static $server;

    /**
     * Get the global server object used throughout the unit tests of /* Replaced /* Replaced /* Replaced Guzzle */ */ */
     *
     * @return Server
     */
    public static function getServer()
    {
        if (!self::$server) {
            self::$server = new Server();
            if (self::$server->isRunning()) {
                self::$server->flush();
            } else {
                self::$server->start();
            }
        }

        return self::$server;
    }
}
