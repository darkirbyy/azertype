#! /bin/sh

# checking that the user starting the stage environment has admin privileges
if [ "$(id -u)" -ne 0 ]; then
    echo "Stage must me run as root or with sudo privileges"
    exit 1
fi

# checking that the folder and the runner are not already there
if [ -d ".docker" ] ||
   [ "$( sudo docker container inspect -f '{{.State.Status}}' nginx-test )" = "running" ] || 
   [ "$( sudo docker container inspect -f '{{.State.Status}}' php-fpm-test )" = "running" ];  then
    echo "Previous stage is still running, use stage-stop.sh"
    exit 1
fi

# prepare the new docker folder
mkdir .docker && mkdir .docker/api && mkdir .docker/log

# copy all front/back end folder to their respective destination
cp -r html .docker/html
cp -r public .docker/api/public
cp -r src .docker/api/src
cp -r .vendor .docker/api/.vendor

# copy the .env file for php script in back-end, and change some constants
cp .env-example .docker/api/.env
sed -i -E 's#^APP_ENV=.+#APP_ENV="stage"#' '.docker/api/.env'
sed -i -E 's#^GENERATOR_NAME=.+#GENERATOR_NAME="Hero"#' '.docker/api/.env'
sed -i -E 's#^WORDS_PER_DRAW=.+#WORDS_PER_DRAW=4#' '.docker/api/.env'
sed -i -E 's#^TIME_INTERVAL=.+#TIME_INTERVAL="00:02:00"#' '.docker/api/.env'
sed -i -E 's#^API_URL=.+#API_URL="http://localhost:8001"#' '.docker/api/.env'
sed -i -E 's#^API_URI=.+#API_URI="/api/public/"#' '.docker/api/.env'

# Generate the env.js file for js script in front-end according to .env in back-end
echo "// Generated by start.sh for stage environment" > .docker/html/scripts/env.js
sed -n 's/^API/const &/p' '.docker/api/.env' >> .docker/html/scripts/env.js
sed -i 's#(DEV)#(STAGE)#' '.docker/html/index.html'

# build and launch the containers using docker then wait for all containers to be up
docker compose -f stage/compose.yml up -d
echo "Waiting for log files to be created..."
while [ ! -f .docker/log/php-fpm-access.log ] || [ ! -f .docker/log/php-fpm-error.log ]; do
    sleep 2
done

# grant write privileges to all user, to allow modifying stage files on the fly and read logs
chmod -R a+rw .docker/
echo "Stage environment started (http://localhost:8001)"