FROM composer:latest as setup

RUN mkdir //* Replaced /* Replaced /* Replaced guzzle */ */ */

WORKDIR //* Replaced /* Replaced /* Replaced guzzle */ */ */

RUN set -xe \
    && composer init --name=/* Replaced /* Replaced /* Replaced guzzle */ */ */http/test --description="Simple project for testing /* Replaced /* Replaced /* Replaced Guzzle */ */ */ scripts" --author="Márk Sági-Kazár <mark.sagikazar@gmail.com>" --no-interaction \
    && composer require /* Replaced /* Replaced /* Replaced guzzle */ */ */http//* Replaced /* Replaced /* Replaced guzzle */ */ */


FROM php:7.3

RUN mkdir //* Replaced /* Replaced /* Replaced guzzle */ */ */

WORKDIR //* Replaced /* Replaced /* Replaced guzzle */ */ */

COPY --from=setup //* Replaced /* Replaced /* Replaced guzzle */ */ */ //* Replaced /* Replaced /* Replaced guzzle */ */ */
