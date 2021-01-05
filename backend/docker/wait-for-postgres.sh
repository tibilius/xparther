#!/bin/bash
# wait-for-postgres.sh

set -e

cd /var/www/symfony/repo/branch/backend

until app/console doctrine:query:sql 'select id from users limit 1'; do
  >&2 echo "Postgres is unavailable - sleeping"
  sleep 1
done

>&2 echo "Postgres is up - executing command '$@'"

exec $@
