#!/usr/bin/env bash

# Create application specific folder structure
mkdir -p \
  /var/www/symfony/cache \
  /var/www/symfony/data \
  /var/www/symfony/logs \
  /var/www/symfony/logs_app \
  /var/www/symfony/tmp/

chmod -R 777 \
  /var/www/symfony/cache \
  /var/www/symfony/data \
  /var/www/symfony/logs \
  /var/www/symfony/logs_app \
  /var/www/symfony/tmp

usermod -u 1000 www-data

chown -R www-data:www-data \
  /var/www/symfony/cache \
  /var/www/symfony/data \
  /var/www/symfony/logs \
  /var/www/symfony/logs_app \
  /var/www/symfony/tmp \
  /var/www/symfony/repo/branch/backend/app/jwt

cp ./docker/.env.dev .env

# Enter work directory
cd /var/www/symfony/repo/branch/backend

# Install assets by symlink
su www-data -s /bin/bash -c 'app/console assets:install --env=prod --symlink --relative'

# Rebuilt cache
su www-data -s /bin/bash -c 'app/console cache:clear --no-warmup --env=prod'
su www-data -s /bin/bash -c 'app/console cache:warmup  --env=prod'

su www-data -s /bin/bash -c 'app/console cache:clear --no-warmup --env=test'
su www-data -s /bin/bash -c 'app/console cache:warmup  --env=test'

exec php-fpm -F
