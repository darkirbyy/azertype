FROM php:8.2-fpm

# Update and install APCU
RUN apt-get update && apt-get install -y \
    && pecl install apcu \
    && docker-php-ext-enable apcu

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
