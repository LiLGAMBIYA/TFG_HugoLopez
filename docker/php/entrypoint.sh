#!/usr/bin/env sh
set -e

if [ ! -d vendor ] || [ ! -f vendor/autoload.php ]; then
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

mkdir -p var/cache var/log
chown -R www-data:www-data var || true

php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration || true
php bin/console app:load-demo-data --no-interaction || true
php bin/console cache:clear --no-warmup || true

exec "$@"
