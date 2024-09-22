#! /bin/bash

sudo rm -rf .docker

sudo docker stop nginx-test
sudo docker stop php-fpm-test

echo "Stage environment stopped"

exit 0
