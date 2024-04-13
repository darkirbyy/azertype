<?php

namespace Azertype;
require_once $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

class CacheInFile{

    protected string $cacheFileName;

    /*
    If the cache directory don't exist, create it
    */ 
    function __construct(string $cacheFileName){
        $this->cacheFileName = $cacheFileName;

    }

    protected function getCacheFilePath() : string {
        return Config::getRootPath().Config::CACHE_DIRNAME.$this->cacheFileName;
    }

    /*
    If the cached file exists, can be read and is not empty,
    decode the json and return the array, null otherwise
    */ 
    public function read() : ?array {
        $cacheFilePath = $this->getCacheFilePath();
        if(!file_exists($cacheFilePath))
            return null;
        $data = file_get_contents($cacheFilePath);
        if($data === false || strlen($data) === 0)
            return null;
        else
            return json_decode($data, true);
    }

    /*
    If the array is not null, not empty and the write
    operation succeed return true, false otherwise
    */ 
    public function store(?array $data) : bool {
        $cacheFilePath = $this->getCacheFilePath();
        /*if (!is_dir($cacheDirPath)) {
            mkdir($cacheDirPath);       
        } */
        if ($data === null || empty($data)) 
            return false;
        else
            return file_put_contents($cacheFilePath, json_encode($data), LOCK_EX);
    }
}