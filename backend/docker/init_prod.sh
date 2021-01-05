#!/usr/bin/env bash

sed -i -e 's/= www-data/= root/g' /usr/local/etc/php-fpm.d/www.conf
sed -i -e 's/;pm.max_requests = 500/pm.max_requests = 500/g' /usr/local/etc/php-fpm.d/www.conf
sed -i -e 's/pm.max_children = 5/pm.max_children = 25/g' /usr/local/etc/php-fpm.d/www.conf
sed -i -e 's/srv\/www\/facebook-aitarget\/jwt/var\/www\/symfony\/repo\/branch\/backend\/app\/jwt/g' /var/www/symfony/repo/branch/backend/.env

/var/www/symfony/repo/branch/backend/bin/console cache:warmup  --env=prod

exec php-fpm -R
