FROM php:8.4.10-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git && \
    docker-php-ext-install pdo pdo_mysql zip ctype curl dom mbstring openssl xml && \
    a2enmod rewrite

WORKDIR /var/www/html
COPY . .
RUN chown -R www-data:www-data /var/www

EXPOSE 80