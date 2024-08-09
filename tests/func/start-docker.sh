#! /bin/sh

rm -rf .docker
mkdir .docker && mkdir .docker/api && mkdir .docker/log

cp -r dist .docker/html
cp -r public .docker/api/public
cp -r src .docker/api/src
cp -r .vendor .docker/api/.vendor
cp -r database .docker/api/database
cp .env .docker/api/.env

docker compose -f tests/func/compose.yml up -d