#!/bin/bash
shopt -s globstar

# prepare shipping dir
mkdir dist && mkdir dist/database
cp -r html public src .vendor dist
cp database/words.db dist/database/

# copy the .env file for php script in back-end, and change some constants
cp .env-example dist/.env
sed -i -E 's#^APP_ENV=.+#APP_ENV="PROD"#' 'dist/.env'
sed -i -E 's#^GENERATOR_NAME=.+#GENERATOR_NAME="Self"#' 'dist/.env'
sed -i -E 's#^WORDS_PER_DRAW=.+#WORDS_PER_DRAW=10#' 'dist/.env'
sed -i -E 's#^TIME_INTERVAL=.+#TIME_INTERVAL="00:05:00"#' 'dist/.env'
sed -i -E 's#^API_URL=.+#API_URL="https://darkirby.ddns.net"#' 'dist/.env'
sed -i -E 's#^API_URI=.+#API_URI="/api/azertype/"#' 'dist/.env'

# Generate the env.js file for js script in front-end according to .env in back-end
echo "// Generated by copy-dist.sh for prod environment" > dist/html/scripts/env.js
sed -n 's/^API/const &/p' 'dist/.env' >> dist/html/scripts/env.js
sed -i 's#(DEV)##' 'dist/html/index.html'