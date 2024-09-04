# Azertype

Little fun project, starting from openclassroom tutorial while learning js, then going deeper with a php back-end.

## Develop

Install php (at least version 8.1, with sqlite and curl extension) and sqlite3.  
Clone the repository <https://github.com/darkirbyy/azertype.git> from GitHub.  
Go in the root directory of the project.  

To build the app, run `composer install`.  

To start the app:

- run `php -S localhost:8000 -t public` on a terminal.
- open `dist/index.html` with any browser.

## Test

The tests use their own database and cache directory, and the relevant environment constants are redefined in the **phpunit.xml** file.

- To run unit tests : `composer tests-unit`. Logs and code coverage are stored in *.test-results*.  
- To run integration tests : `composer tests-inte`. No log/coverage.  
- To run both, use the shorthand : `composer tests-all`.

## Stage

Require docker and admin privileges. In the root directory :

- run `sudo ./stage/start.sh` on a terminal.
- open `localhost:8001` with any browser.
- run `sudo ./stage/stop.sh` on a terminal.

## Deploy

To install back on prod :

- install php , sqlite3
- set warning to false ? use php.ini-developpment / php.ini-production

## Todo

- use acpu to cache
- write my own word generator
- add "best time" for each draw
