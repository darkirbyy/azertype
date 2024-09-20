# Azertype

Little fun project, starting from openclassroom tutorial while learning js, then going deeper with a php back-end.

## Requirements

- PHP, at least version 8.2, with sqlite3/curl/apcu extensions.  
Don't forget to enable APCU in CLI by adding `apc.enable_cli=1` to the **php.ini** file.
- Sqlite3.
- Composer.
- Docker and admin privileges for stage environment.

## Develop

Clone the repository <https://github.com/darkirbyy/azertype.git> from GitHub.  
Go in the root directory of the project.  

To build the app, run `composer install`.  

To start the app:

- run `php -S localhost:8000 -t public` on a terminal.
- open `html/index.html` with any browser.
- PHP logs are written through the console running the CLI server.

## Test

The tests use their own database and cache directory, and the relevant environment constants are redefined in the **phpunit.xml** file.

- To run unit tests : `composer tests-unit`. Logs and code coverage are stored in *.test-results*.  
- To run integration tests : `composer tests-inte`. No log/coverage.  
- To run both, use the shorthand : `composer tests-all`.

## Stage

In the root directory :

- to build and start the stage environment, run `sudo ./stage/start.sh` on a terminal (a temporary **.docker** directory is created).
- open `localhost:8001` with any browser and test manually
- PHP and Nginx logs are stored in **.docker/log**.
- to stop the stage environment, run `sudo ./stage/stop.sh`.

## Deploy

To prepare the production server :

- **Back-end** : require php (>=8.2, with sqlite3, curl, apcu extensions) and sqlite3. For any request to the API, redirect to **public/index.php** keeping the inital URI, with php-fpm for example. Use the **.env-example** file to generate a valid **.env** file at the root directory of the project.
- **Front-end** : serve the page *index.html* in the **html** folder with nginx or apache for example. Use the **.env** previously generated to replace the **html/scripts/env.js** file with the correct API constants.

Work-flows configured with GitHub actions :

- When pushing to main : build and perform all tests
- When pushing a tag starting with **v** : build perform all tests, then remove dev packages and send to production server.

## To do

- use FileCache if ApcuCache not available ? or add a new .env variable ?
- add "best time" for each draw
- write better integration tests (without using phpunit ? with postman ?)
- write functional tests (using playwright ?)
