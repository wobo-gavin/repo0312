<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Server.php';

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Server;

Server::start();

register_shutdown_function(function () {
    Server::stop();
});
