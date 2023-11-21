FROM php:8.2-fpm-alpine
RUN apk add icu-dev
RUN docker-php-ext-install -j$(nproc) intl \
    pdo \
    pdo_mysql \
    mysqli

COPY --from=composer /usr/bin/composer /usr/local/bin/composer