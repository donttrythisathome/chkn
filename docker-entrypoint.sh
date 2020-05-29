#!/bin/bash
set -e

echo "Start migrations"
php artisan migrate --force
echo "End migrations"

exec "$@"
