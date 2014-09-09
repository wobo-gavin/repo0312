<?php
/*
 * Runs a performance test against the node.js server for both serial and
 * parallel requests. Requires PHP 5.5 or greater.
 *
 *     # Basic usage
 *     make perf
 *     # With custom options
 *     REQUESTS=100 PARALLEL=5000 make perf
 */

require __DIR__ . '/bootstrap.php';

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Server;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Ring\Client\CurlMultiAdapter;

// Wait until the server is responding
Server::wait();

// Get custom make variables
$total = isset($_SERVER['REQUESTS']) ? $_SERVER['REQUESTS'] : 1000;
$parallel = isset($_SERVER['PARALLEL']) ? $_SERVER['PARALLEL'] : 100;

$/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['base_url' => Server::$url]);

$t = microtime(true);
for ($i = 0; $i < $total; $i++) {
    $/* Replaced /* Replaced /* Replaced client */ */ */->get('//* Replaced /* Replaced /* Replaced guzzle */ */ */-server/perf');
}
$totalTime = microtime(true) - $t;
$perRequest = ($totalTime / $total) * 1000;
printf("Serial: %f (%f ms / request) %d total\n",
    $totalTime, $perRequest, $total);

// Create a generator used to yield batches of requests to sendAll
$reqs = function () use ($/* Replaced /* Replaced /* Replaced client */ */ */, $total) {
    for ($i = 0; $i < $total; $i++) {
        yield $/* Replaced /* Replaced /* Replaced client */ */ */->createRequest('GET', '//* Replaced /* Replaced /* Replaced guzzle */ */ */-server/perf');
    }
};

$t = microtime(true);
$/* Replaced /* Replaced /* Replaced client */ */ */->sendAll($reqs(), ['parallel' => $parallel]);
$totalTime = microtime(true) - $t;
$perRequest = ($totalTime / $total) * 1000;
printf("Batch:  %f (%f ms / request) %d total with %d in parallel\n",
    $totalTime, $perRequest, $total, $parallel);

$adapter = new CurlMultiAdapter(['max_handles' => $parallel]);
$/* Replaced /* Replaced /* Replaced client */ */ */ = new Client(['adapter' => $adapter, 'base_url' => Server::$url]);
$t = microtime(true);
for ($i = 0; $i < $total; $i++) {
    $/* Replaced /* Replaced /* Replaced client */ */ */->get('//* Replaced /* Replaced /* Replaced guzzle */ */ */-server/perf');
}
unset($/* Replaced /* Replaced /* Replaced client */ */ */);
$totalTime = microtime(true) - $t;
$perRequest = ($totalTime / $total) * 1000;
printf("Future: %f (%f ms / request) %d total\n",
    $totalTime, $perRequest, $total);
