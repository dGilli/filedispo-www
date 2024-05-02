FROM php:8.3-apache

ENV APACHE_DOCUMENT_ROOT /var/app/web

RUN a2enmod rewrite

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN mkdir -p /var/cache/app \
    && chown www-data:www-data /var/cache/app \
    && chmod 755 /var/cache/app

WORKDIR /var/app

COPY . .

