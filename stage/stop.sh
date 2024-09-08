#! /bin/sh

rm -rf .docker

docker stop nginx-test
docker stop php-fpm-test

echo "Stage environment stopped"

exit 0
