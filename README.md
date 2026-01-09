# Azertype

![version](https://img.shields.io/endpoint?url=https://gist.githubusercontent.com/darkirbyy/07bb4b086f8e7dea73754e73bc5c1bb2/raw/azertype-version.json)
![coverage](https://img.shields.io/endpoint?url=https://gist.githubusercontent.com/darkirbyy/07bb4b086f8e7dea73754e73bc5c1bb2/raw/azertype-coverage.json)

Little test project from openclassroom while learning php/js.

## Requirements

- PHP, at least version 8.4, with sqlite3/curl/apcu extensions.  
Don't forget to enable APCU in CLI by adding `apc.enable_cli=1` to the **php.ini** file.
- Composer.
- Sqlite3.
- Docker and admin privileges for stage environment.

## Develop

Clone the repository <https://github.com/darkirbyy/azertype.git> from GitHub.  
Go in the root directory of the project.  

To build, run `composer install`.  
To configure, change the values in the **.env** file.
To start :

- run `composer server` on a terminal.
- open `html/index.html` with any browser.
- PHP logs are written through the console running the CLI server.

## Test

The tests use their own database and cache directory, and the relevant environment constants are redefined in the **phpunit.xml** file.

- To run unit tests : `composer tests-unit`. Logs and code coverage are stored in *.test-results*.  
- To run integration tests : `composer tests-inte`. Logs are stored in *.test-results*.
- To run both, use the shorthand : `composer tests-all`.

## Stage

In the root directory :

- to build and start the stage environment, run `sudo ./stage/start.sh` on a terminal (a temporary **.docker** directory is created).
- open `localhost:8001` with any browser and test manually
- PHP and Nginx logs are stored in **.docker/log**.
- to stop the stage environment, run `sudo ./stage/stop.sh`.

## Deploy

To prepare the production server :

- **Back-end** : require php (>=8.4, with sqlite3, curl, apcu extensions) and sqlite3. For any request to the API, redirect to **public/index.php** keeping the inital URI, with php-fpm for example. Use the **.env-example** file to generate a valid **.env** file at the root directory of the project.
- **Front-end** : serve the page *index.html* in the **html** folder with nginx or apache for example. Use the **.env** previously generated to replace the **html/scripts/env.js** file with the correct API constants.

Work-flows configured with GitHub actions :

- When pushing on main : build, perform all tests, then remove dev packages and send to production server.
