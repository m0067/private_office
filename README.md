Private office
==============

A private office application with access via REST API. For API requests, a token must be used. A blocked user cannot use the API.
To view the operations, there are methods `GET api/transfer`, `GET api/transfer/{transfer}`, for transferring funds `POST api/transfer`.
All functionality can be viewed in the folder `tests/Feature`

## Quick start

### Run commands

```bash
$ composer install
$ php artisan migrate
```

### Run tests

All

```bash
$ php vendor/bin/phpunit
```