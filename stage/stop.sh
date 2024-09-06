#! /bin/sh

rm -rf .docker

docker stop nginx-test
docker stop php-fpm-test

exit 0
