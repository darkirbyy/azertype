<?php

class Cache{

    const CACHE_DIRECTORY = ".database.cache/";
    protected string $cachePath;

    function __construct(string $cacheName){
        $this->cachePath = $_SERVER['DOCUMENT_ROOT'].'/cache/'.$cacheName;
        if(!file_exists($this->cachePath))
            fopen($this->cachePath, "w");
    }

    function read() : ?array {
        $data = file_get_contents($this->cachePath);
        return ($data !== false && strlen($data) > 0) ? json_decode($data, true) : null;
    }

    function store(array $data) : bool {
        return file_put_contents($this->cachePath, json_encode($data), LOCK_EX);
    }
}