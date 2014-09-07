<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor//* Replaced /* Replaced /* Replaced guzzle */ */ */http/ring/tests/Client/Server.php';

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Server;

Server::start();

register_shutdown_function(function () {
    Server::stop();
});
