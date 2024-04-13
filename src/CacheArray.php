<?php

namespace Azertype;

class CacheArray{

    protected string $cacheFilePath;

    /*
    If the cache directory don't exist, create it
    */ 
    function __construct(string $cacheFileName){
        $cacheDirPath = Config::ROOT.Config::CACHE_DIRNAME;
        $this->cacheFilePath = $cacheDirPath.$cacheFileName;
        if (!is_dir($cacheDirPath)) {
            mkdir($cacheDirPath);       
        } 
    }

    /*
    If the cached file exists and can be read,
    decode the json and return the array, null otherwise
    */ 
    public function read() : ?array {
        if(!file_exists($this->cacheFilePath))
            return null;
        $data = file_get_contents($this->cacheFilePath);
        if($data === false)
            return null;
        else
            return json_decode($data, true);
    }

    /*
    If the array is not null and the write operation succeed, 
    return true, false otherwise
    */ 
    public function store(?array $data) : bool {
        if ($data === null) 
            return false;
        else
            return file_put_contents($this->cacheFilePath, json_encode($data), LOCK_EX);
    }
}