FROM composer:latest as setup

WORKDIR //* Replaced /* Replaced guzzle */ */

RUN set -xe \
    && composer init --name=/* Replaced /* Replaced guzzle */ */http/test --description="Simple project for testing /* Replaced /* Replaced Guzzle */ */ scripts" --author="Márk Sági-Kazár <mark.sagikazar@gmail.com>" --no-interaction \
    && composer require /* Replaced /* Replaced guzzle */ */http//* Replaced /* Replaced guzzle */ */


FROM php:7.3

WORKDIR //* Replaced /* Replaced guzzle */ */

COPY --from=setup //* Replaced /* Replaced guzzle */ */ //* Replaced /* Replaced guzzle */ */
