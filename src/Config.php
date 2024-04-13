<?php

namespace Azertype;

class Config{
    const ROOT = __DIR__.DIRECTORY_SEPARATOR.
                "..".DIRECTORY_SEPARATOR;
    
    const CACHE_DIRNAME = ".db.cache".DIRECTORY_SEPARATOR;

    const DB_DIRECTORY = "db".DIRECTORY_SEPARATOR;
    const DB_FILENAME = "db1.sqlite";
}

/*
define('ROOT',dirname(__DIR__).DIRECTORY_SEPARATOR);
define('CACHE_DIRNAME',".db.cache".DIRECTORY_SEPARATOR);
define('DB_DIRECTORY',"db".DIRECTORY_SEPARATOR);
define('DB_FILENAME',"db1.sqlite");
*/