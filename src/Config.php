<?php

namespace Azertype;

class Config{
    const ROOT = __DIR__.DIRECTORY_SEPARATOR.
                "..".DIRECTORY_SEPARATOR;
    
    const FILECACHE_DIRNAME = ".file.cache".DIRECTORY_SEPARATOR;

    const DB_DIRECTORY = "db".DIRECTORY_SEPARATOR;
    const DB_FILENAME = "db1.sqlite";

    // between 1 and 1440, best to be a divisor of 1440
    const INTERVAL_BETWEEN_GAME = 1;
}

