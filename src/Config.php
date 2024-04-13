<?php

namespace Azertype;

class Config{

    const CACHE_DIRNAME = ".db.cache".DIRECTORY_SEPARATOR;

    const DB_DIRECTORY = "db".DIRECTORY_SEPARATOR;
    const DB_FILENAME = "db1.sqlite";

    static public function getRootPath() : string {
        return $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR;
    }

}