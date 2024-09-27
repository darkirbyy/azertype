<?php

namespace Azertype\Cache;

class ApcuCache extends AbstractCache{

    private string $key;

    /**
     *  Initialize the cache
     * 
     * @param string $key The key identifying the cache
     */
    function __construct(string $key){
        $this->key = $key;
    }


    /**
     *  If the apcu variable exists and can be read,
     *  return it, null otherwise
     *       
     * @return ?array
     */
    public function read() : ?array {
        if (!apcu_exists($this->key))
            return null;
        return apcu_fetch($this->key);
    }

    /**
     *  If the array is not null and the cache operation succeed, 
     *  return true, false otherwise
     * 
     * @param array $data The array to store in cache
     * 
     * @return bool
     */
    public function store(?array $data) : bool {
        if ($data === null) 
            return false;
        else
            return apcu_store($this->key, $data);
    }


    /**
     *  Delete the cache variable if exists and return true if succeed
     * 
     * @return bool
     */ 
    public function clear() : bool {
        if (!apcu_exists($this->key))
            return true;
        return apcu_delete($this->key); 
    }
}