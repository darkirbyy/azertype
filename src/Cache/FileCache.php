<?php

namespace Azertype\Cache;
use Azertype\Config;

class FileCache extends AbstractCache{

    private string $filePath;

    /*
    If the cache directory don't exist, create it
    */ 
    function __construct(string $dirPath, string $fileName){
        $this->filePath = $dirPath.$fileName;
        if (!is_dir($dirPath)) {
            mkdir($dirPath);       
        } 
    }

    /*
    If the cached file exists and can be read,
    decode the json and return the array, null otherwise
    */ 
    public function read() : ?array {
        if(!file_exists($this->filePath))
            return null;
        $data = file_get_contents($this->filePath);
        if($data === false || ($decode = json_decode($data, true)) === null)
            return null;
        else
            return $decode;
    }

    /*
    If the array is not null and the write operation succeed, 
    return true, false otherwise
    */ 
    public function store(?array $data) : bool {
        if ($data === null) 
            return false;
        else
            return file_put_contents($this->filePath, json_encode($data), LOCK_EX);
    }


    /*
    Delete the cache file if exists
    */ 
    public function clear() : bool {
        if (!file_exists($this->filePath))
            return true;
        return unlink($this->filePath); 
    }
}