${service.name} Web Service Client for PHP
==========================================

Interact with the ${service.name} web service using the /* Replaced /* Replaced /* Replaced Guzzle */ */ */ framework for
building RESTful web service /* Replaced /* Replaced /* Replaced client */ */ */s in PHP.

## Installation

Add this service to the src/${service.relative_path} directory of your /* Replaced /* Replaced /* Replaced Guzzle */ */ */
installation:

    cd /path/to//* Replaced /* Replaced /* Replaced guzzle */ */ */
    git submodule add ${service.git_url} ./src/${service.relative_path}

You can now build a phar file containing this /* Replaced /* Replaced /* Replaced client */ */ */ and the main /* Replaced /* Replaced /* Replaced guzzle */ */ */ framework:

    cd /path/to//* Replaced /* Replaced /* Replaced guzzle */ */ *//build
    phing phar

Now you just need to include /* Replaced /* Replaced /* Replaced guzzle */ */ */.phar in your script.  The phar file
will take care of autoloading /* Replaced /* Replaced /* Replaced Guzzle */ */ */ classes:

    <?php
    require_once '/* Replaced /* Replaced /* Replaced guzzle */ */ */.phar';

## Testing

Run the phing build script to configure your project for PHPUnit testing:

    phing

You will be prompted for the full path to your git clone of the main /* Replaced /* Replaced /* Replaced Guzzle */ */ */
framework.

### More information

- See https://github.com//* Replaced /* Replaced /* Replaced guzzle */ */ *///* Replaced /* Replaced /* Replaced guzzle */ */ */ for more information about /* Replaced /* Replaced /* Replaced Guzzle */ */ */, a PHP framework for building RESTful webservice /* Replaced /* Replaced /* Replaced client */ */ */s.