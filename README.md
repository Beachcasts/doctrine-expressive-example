# doctrine-expressive-example
This is an example application using Zend Expressive and Doctrine ORM connecting to a MySQL DB.

* Step 1 - Clone this repo to desired location
* Step 2 - Run composer install
* Step 3 - Run docker-compose
* Step 4 - copy/rename the following config files
    * /config/autoload/development.local.php.dist -> development.local.php
    * /config/autoload/doctrine.local.php.dist -> doctrine.local.php
    * /config/autoload/local.php.dist -> local.php
    * /config/development.config.php.dist -> development.config.php
    
At this point the REST API should work.

* http://localhost:8080/announcements
* http://localhost:8080/banks
* http://localhost:8080/branches

Each of these can be called as an HTTP GET, or can be called as an HTTP POST with added fields to populate the DB.

In addition to these endpoints the hypermedia in each response provides information to additional endpoints.

Also, the Zend Expressive CLI and Doctrine CLI commands are available by gaining the terminal from Docker.

`$ docker exec -i -t {container-id} bash`

Change {container-id} with the Docker container ID.

Then CLI tools are available:

* `$ php vendor/bin/doctrine list`
* `$ php vendor/bin/doctrine-dbal list`
* `$ php vendor/bin/expressive`
