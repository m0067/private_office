Private office
==============

A private office application with access via REST API. For API requests, a token must be used. A blocked user cannot use the API.
To view the operations, there are methods `GET api/transfer`, `GET api/transfer/{transfer}`, for transferring funds `POST api/transfer`.
All functionality can be viewed in the folder `tests/Feature`

## Quick start

### Run commands

```bash
$ mysql -h${DB_HOST} -u${DB_USERNAME} -p${DB_PASSWORD} -e "CREATE DATABASE IF NOT EXISTS ${DB_DATABASE} /*\!40100 DEFAULT CHARACTER SET utf8 */;"
$ composer install
$ php artisan migrate
```

or if you have docker
```bash
docker-compose exec private_office_php ./init.sh
```

### Run tests

All

```bash
$ php vendor/bin/phpunit
```