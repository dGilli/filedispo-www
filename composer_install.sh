#!/bin/bash

# Check for `composer.lock` & `vendor/autoload.php`
cd /var/www
if [ ! -f "composer.lock" ] || [ ! -f "vendor/autoload.php" ]; then
    # Composer install
    sudo -u www-data COMPOSER_HOME=/var/cache/app/composer composer install \
        --verbose --no-progress --no-scripts --optimize-autoloader --no-interaction
fi

