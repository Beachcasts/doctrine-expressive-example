# doctrine-expressive-example
This is an example application using Zend Expressive and Doctrine ORM connecting to a MySQL DB.

## Installation

* Step 1 - Clone this repo to desired location
* Step 2 - Run docker-compose
* Step 3 - copy/rename the following config files
    * /config/autoload/development.local.php.dist >>> development.local.php
    * /config/autoload/doctrine.local.php.dist >>> doctrine.local.php
    * /config/autoload/local.php.dist >>> local.php
    * /config/development.config.php.dist >>> development.config.php

## Usage

At this point the REST API should work. (The following REST endpoints return a listing from the DB.)

* http://localhost:8080/announcements
* http://localhost:8080/banks
* http://localhost:8080/branches

Each of these can be called as an HTTP GET, or an HTTP POST with Json fields payload. (See example body content in docblocks of Create and Update Handlers.)

In addition to these endpoints the hypermedia in each response provides information to additional endpoints.

## CLI Tools
Also, the Zend Expressive CLI and Doctrine CLI commands are available by gaining the terminal from Docker.

Get the container IDs:
`docker ps`
`docker exec -i -t {container-id} bash`

Change {container-id} with the Docker container ID from the `docker ps`.

Then CLI tools are available:

* `php vendor/bin/doctrine list`
* `php vendor/bin/doctrine-dbal list`
* `php vendor/bin/expressive`
* or `php vendor/doctrine/bin/doctrine list`
