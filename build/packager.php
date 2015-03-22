<?php
require __DIR__ . '/artifacts/Burgomaster.php';

$stageDirectory = __DIR__ . '/artifacts/staging';
$projectRoot = __DIR__ . '/../';
$packager = new \Burgomaster($stageDirectory, $projectRoot);

// Copy basic files to the stage directory. Note that we have chdir'd onto
// the $projectRoot directory, so use relative paths.
foreach (['README.md', 'LICENSE'] as $file) {
    $packager->deepCopy($file, $file);
}

// Copy each dependency to the staging directory. Copy *.php and *.pem files.
$packager->recursiveCopy('src', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http', ['php']);
$packager->recursiveCopy('vendor//* Replaced /* Replaced /* Replaced guzzle */ */ */http/promise/src', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http/Promise');
$packager->recursiveCopy('vendor//* Replaced /* Replaced /* Replaced guzzle */ */ */http/psr7/src', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http//* Replaced /* Replaced /* Replaced Psr7 */ */ */');
$packager->recursiveCopy('vendor/psr/http-message/src', 'Psr\Http\Message');

$packager->createAutoloader([
    '/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http//* Replaced /* Replaced /* Replaced Psr7 */ */ *//functions.php',
    '/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http/Promise/functions.php',
]);

$packager->createPhar(__DIR__ . '/artifacts//* Replaced /* Replaced /* Replaced guzzle */ */ */.phar');
$packager->createZip(__DIR__ . '/artifacts//* Replaced /* Replaced /* Replaced guzzle */ */ */.zip');
