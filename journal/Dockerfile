FROM composer:1.8.4 AS composer

COPY composer.json \
    composer.lock \
    ./

RUN composer --no-interaction install --no-dev --ignore-platform-reqs --no-autoloader --no-suggest --prefer-dist

COPY src/ src/

RUN composer --no-interaction dump-autoload --classmap-authoritative



FROM php:7.0.29-fpm-jessie

WORKDIR /app

RUN docker-php-ext-install opcache

RUN mkdir -p var/cache var/logs web/bundles && \
    chown -R www-data:www-data var web/bundles

COPY bin/ bin/
COPY web/ web/
COPY app/ app/
COPY --from=composer /app/vendor/ vendor/
COPY src/ src/

USER www-data

ARG app_env
ARG api_url
ARG api_url_public
ARG api_key
ENV APP_ENV=$app_env \
    SYMFONY__API_URL=$api_url \
    SYMFONY__API_URL_PUBLIC=$api_url_public \
    SYMFONY__API_KEY=$api_key

RUN bin/console cache:warmup && \
    bin/console assets:install
