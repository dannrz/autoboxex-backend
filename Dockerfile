FROM php:8.3.12-apache-bullseye
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN docker-php-source extract \
    # do important things \
    && docker-php-source delete

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN a2enmod rewrite && service apache2 restart

EXPOSE 80