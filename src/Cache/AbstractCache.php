<?php

namespace Azertype\Cache;

/**
 * All cache must extends this class 
 */ 

abstract class AbstractCache{
    abstract public function read() : ?array;
    abstract public function store(?array $data) : bool;
    abstract public function clear() : bool;
}
