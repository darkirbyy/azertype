<?php

namespace Azertype;
require_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

class CacheInFile{

    protected string $cacheFilePath;

    /*
    If the cache directory don't exist, create it
    */ 
    function __construct(string $cacheFileName){
        $cacheDirPath = Config::getRootPath().Config::CACHE_DIRNAME;
        $this->cacheFilePath = $cacheDirPath.$cacheFileName;
        if (!is_dir($cacheDirPath)) {
            mkdir($cacheDirPath);       
        } 
    }

    /*
    If the cached file exists, can be read and is not empty,
    decode the json and return the array, null otherwise
    */ 
    function read() : ?array {
        if(!file_exists($this->cacheFilePath))
            return null;
        $data = file_get_contents($this->cacheFilePath);
        if($data === false || strlen($data) === 0)
            return null;
        else
            return json_decode($data, true);
    }

    /*
    If the array is not null, not empty and the write
    operation succeed return true, false otherwise
    */ 
    function store(?array $data) : bool {
        if ($data === null || empty($data)) 
            return false;
        else
            return file_put_contents($this->cacheFilePath, json_encode($data), LOCK_EX);
    }
}