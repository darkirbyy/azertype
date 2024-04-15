<?php

namespace Azertype;

class Config{
    // where is the root of the project
    const ROOT = __DIR__.DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR;

    // where is the fixture directory
    const FIXTURE = Config::ROOT.DIRECTORY_SEPARATOR."fixture".DIRECTORY_SEPARATOR;

    // Directory name created at root to store cache filed
    const FILECACHE_DIRNAME = ".file.cache".DIRECTORY_SEPARATOR;

    // Directory and database name created at root to store sqlite file
    const DB_DIRECTORY = "database".DIRECTORY_SEPARATOR;
    const DB_FILENAME = "dev.sqlite";

    // Timezone used to calculate and compare timestamp
    const TIMEZONE = "Europe/Paris";

    // How many minutes between each game
    // (between 1 and 1440, best to be a divisor of 1440)
    const INTERVAL_BETWEEN_GAME = 1;

    // how many words a generated per game
    const WORDS_PER_GAME  = 5;

    // URL of herokuapp api
    const HERO_TRUE_URL = "https://random-word-api.herokuapp.com/word?lang=fr&number=";
    const HERO_FAKE_URL = "http://localhost:8001/fixture/hero.php?n=";

    // Connection to external api timeout in seconds
    const EXTERN_API_TIMEOUT = 2;

}

