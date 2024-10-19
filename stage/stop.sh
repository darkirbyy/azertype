#! /bin/bash

sudo rm -rf .docker

sudo docker compose -f stage/compose.yml down

echo "Stage environment stopped"

exit 0
