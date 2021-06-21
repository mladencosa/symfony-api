Symfony 5 based API will provide users and transactions endpoints. Different approaches are used, some good some bad.

## Requirements

* Docker - if not, you are on your own (you push yourself into your own hell🚽)
* PHP 7.4
* Composer

## API Endpoints

All URIs are relative to *http://localhost:8001*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*UsersController* | *getUsers* | **GET** /api/users/| Get list of all active Austrian users
*UsersController* | *updateUserDetails* | **PUT** /api/users/{id}/edit_details | Update user details if available
*UsersController* | *deleteUser* | **DELETE** /api/users/{id}/delete | Delete user if no user details
*TransactionsController* | *getTransactions* | **GET** /api/transactions?source= db/csv | Get list of transactions. Parameter 'source' must be provides. Available sources "db", "csv".

## Installation & Usage
Open a command console, enter your project directory and execute:

```console
$ git pull https://github.com/mladencosa/symfony-api.git

$ cd symfony-api

# You can change hosts or ports if your local are already used for something. Open docker/ and play with the configs
$ docker-compose up -d

# If nginx, db and php-fpm are running, great...

$ cd src/
$ composer install

# Now we can migrate our tables and seed them
$ php bin/console doctrine:migrations:migrate
$ php bin/console doctrine:fixtures:load

and we are done here..

open http://localhost:8001/  and enjoy our great Api

```

## Tests

To run the tests, use:

```console
# For test environment, create .env.test.local file and setup db connection to your test DB
# Migrate tables and seed them with

$ php bin/console doctrine:migrations:migrate -e test 
$ php bin/console doctrine:fixtures:load -e test

# run tests 
$ php bin/phpunit 
```

## Code check
Before commits, please check the code with the following commands: (ignore mine issues found by phpstan xD those are generic)
```bash
php vendor/bin/phpstan analyze
php vendor/bin/phpcs
```
