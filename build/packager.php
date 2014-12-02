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
$packager->recursiveCopy('vendor/react/promise/src', 'React/Promise');
$packager->recursiveCopy('vendor//* Replaced /* Replaced /* Replaced guzzle */ */ */http/ringphp/src', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http/Ring');
$packager->recursiveCopy('vendor//* Replaced /* Replaced /* Replaced guzzle */ */ */http/streams/src', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http/Stream');
$packager->createAutoloader(['React/Promise/functions.php']);
$packager->createPhar(__DIR__ . '/artifacts//* Replaced /* Replaced /* Replaced guzzle */ */ */.phar');
$packager->createZip(__DIR__ . '/artifacts//* Replaced /* Replaced /* Replaced guzzle */ */ */.zip');
