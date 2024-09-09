# Use the official PHP 8.3 image as the base image
FROM php:8.3-fpm

ARG APP_ENV
ENV APP_ENV=$APP_ENV

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libsqlite3-dev \
    libssl-dev \
    libpq-dev \
    npm \
    libsodium-dev \
    libsasl2-dev \
    librdkafka-dev \
    librabbitmq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip sysvsem sodium \
    && pecl install amqp \
    && docker-php-ext-enable amqp

RUN pecl install redis && docker-php-ext-enable redis

RUN pecl install rdkafka && \
    docker-php-ext-enable rdkafka

RUN if [ "${APP_ENV}" != "prod" ]; then pecl install xdebug && docker-php-ext-enable xdebug; fi
RUN if [ "${APP_ENV}" = "prod" ]; then docker-php-ext-install opcache; fi

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy custom php.ini configuration
COPY ./docker/php/conf/php.ini /usr/local/etc/php/

# Set working directory
WORKDIR /var/www/html

# Expose port 9003 for Xdebug
EXPOSE 9003

CMD ["php-fpm"]
