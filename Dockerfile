FROM composer:latest as setup

WORKDIR //* Replaced guzzle */

RUN set -xe \
    && composer init --name=/* Replaced guzzle */http/test --description="Simple project for testing /* Replaced Guzzle */ scripts" --author="Márk Sági-Kazár <mark.sagikazar@gmail.com>" --no-interaction \
    && composer require /* Replaced guzzle */http//* Replaced guzzle */


FROM php:7.3

WORKDIR //* Replaced guzzle */

COPY --from=setup //* Replaced guzzle */ //* Replaced guzzle */
