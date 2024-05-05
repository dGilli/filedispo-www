FROM php:8.3-apache

ENV PUBLIC_FILESHARE_DIR=public

# Install packages
RUN set -eux; \
    apt-get update; \
    # Packages to install
    apt-get install -y \
        sudo \
        zip \
        unzip \
        git \
    && \
    # Grant www-data passwordless sudo
    echo "www-data ALL=(ALL) NOPASSWD:ALL" >> /etc/sudoers \
    && \
    # Install Composer
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer \
    && \
    # Clean out directories that don't need to be part of the image
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR /var/www

COPY . .

RUN a2enmod rewrite \
    && \
    ln -sf /var/www/site.conf /etc/apache2/sites-enabled/000-default.conf

RUN mkdir -p /var/cache/app \
    && \
    chown -R www-data:www-data /var/cache/app
RUN chmod +x composer_install.sh

EXPOSE 80

CMD mkdir -p /var/www/${PUBLIC_FILESHARE_DIR} \
    && \
    /var/www/composer_install.sh \
    && \
    apache2ctl -D FOREGROUND
