FROM composer:1.8.4 AS composer

COPY composer.json \
    composer.lock \
    ./

RUN composer --no-interaction install --classmap-authoritative --no-dev --ignore-platform-reqs --no-suggest --prefer-dist



FROM php:7.0.29-cli-jessie

WORKDIR /app

RUN docker-php-ext-install opcache

COPY src/ src/
COPY web/ web/
COPY data/ data/
COPY --from=composer /app/vendor/ vendor/

USER www-data
CMD ["php", "-S", "0.0.0.0:8080", "-t", "web/"]
