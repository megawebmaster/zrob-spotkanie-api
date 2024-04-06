#!/bin/sh
set -e

# Configure Nginx
envsubst '$PORT' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf

# Optimize app
composer dump-autoload --optimize

# Run migrations
./artisan migrate --force --no-interaction

exec "$@"
