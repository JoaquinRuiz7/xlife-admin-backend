# syntax=docker/dockerfile:1

FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --no-scripts \
    --optimize-autoloader

COPY . .

RUN composer dump-autoload --optimize


FROM php:8.3-fpm-alpine AS runtime

WORKDIR /var/www/html

RUN apk add --no-cache \
    icu-libs \
    libzip \
    && apk add --no-cache --virtual .build-deps \
    icu-dev \
    libzip-dev \
    $PHPIZE_DEPS \
    && docker-php-ext-install \
        pdo_mysql \
        intl \
        zip \
        bcmath \
        opcache \
    && apk del .build-deps

RUN addgroup -g 1000 laravel \
    && adduser -G laravel -g laravel -s /bin/sh -D -u 1000 laravel

COPY --from=vendor /app /var/www/html

COPY ./docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh \
    && chown -R laravel:laravel /var/www/html \
    && chmod -R ug+rwx storage bootstrap/cache

USER laravel

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]

CMD ["php-fpm"]
