<?php

class Cache{

    const CACHE_DIRECTORY = "cache/";
    protected string $cachePath;

    function __construct(string $cacheName){
        $this->cachePath = $_SERVER['DOCUMENT_ROOT'].'/cache/'.$cacheName;
        if(!file_exists($this->cachePath))
            fopen($this->cachePath, "w");
    }

    function read() : ?string {
        $data = file_get_contents($this->cachePath);
        return ($data !== false) ? $data : null;
    }

    function store(mixed $data) : bool {
        return file_put_contents($this->cachePath, $data, LOCK_EX);
    }
}