<?php

namespace Azertype\Cache;

class FileCache extends AbstractCache{

    private string $filePath;

    /**
     *  If cache directory doesn't exist, create it 
     * 
     * @param string $fileName The name of the file that will store the array
     */
    function __construct(string $fileName){
        $dirPath = $_ENV['REL_ROOT'].$_ENV['CACHEFILE_DIR'];
        $this->filePath = $dirPath.$fileName.'.json';
        if (!is_dir($dirPath)) {
            mkdir($dirPath);       
        } 
    }

    /**
     *  If the cached file exists and can be read,
     *  decode the json and return the array, null otherwise
     *       
     * @return ?array
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

    /**
     *  If the array is not null and the write operation succeed, 
     *  return true, false otherwise
     * 
     * @param array $data The array to store in cachee
     * 
     * @return bool
     */
    public function store(?array $data) : bool {
        if ($data === null) 
            return false;
        else
            return file_put_contents($this->filePath, json_encode($data), LOCK_EX);
    }


    /**
     *  Delete the cache file if exists and return true if succeed
     * 
     * @return bool
     */ 
    public function clear() : bool {
        if (!file_exists($this->filePath))
            return true;
        return unlink($this->filePath); 
    }
}