#!/usr/bin/env bash

source .env

mysql -h${DB_HOST} -u${DB_USERNAME} -p${DB_PASSWORD} -e "CREATE DATABASE IF NOT EXISTS ${DB_DATABASE} /*\!40100 DEFAULT CHARACTER SET utf8 */;"

composer install
php artisan migrate
