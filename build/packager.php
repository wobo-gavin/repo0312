<?php
require __DIR__ . '/artifacts/Burgomaster.php';

// Creating staging directory at /* Replaced /* Replaced /* Replaced guzzle */ */ */http/src/build/artifacts/staging.
$stageDirectory = __DIR__ . '/artifacts/staging';
// The root of the project is up one directory from the current directory.
$projectRoot = __DIR__ . '/../';
$packager = new \Burgomaster($stageDirectory, $projectRoot);

// Copy basic files to the stage directory. Note that we have chdir'd onto
// the $projectRoot directory, so use relative paths.
foreach (['README.md', 'LICENSE'] as $file) {
    $packager->deepCopy($file, $file);
}

// Copy each dependency to the staging directory. Copy *.php and *.pem files.
$packager->recursiveCopy('src', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http', ['php', 'pem']);
$packager->recursiveCopy('vendor//* Replaced /* Replaced /* Replaced guzzle */ */ */http/streams/src', '/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http/Stream');
// Create the classmap autoloader, and instruct the autoloader to
// automatically require the '/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http/functions.php' script.
$packager->createAutoloader(['/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http/functions.php']);
// Create a phar file from the staging directory at a specific location
$packager->createPhar(__DIR__ . '/artifacts//* Replaced /* Replaced /* Replaced guzzle */ */ */.phar');
// Create a zip file from the staging directory at a specific location
$packager->createZip(__DIR__ . '/artifacts//* Replaced /* Replaced /* Replaced guzzle */ */ */.zip');
