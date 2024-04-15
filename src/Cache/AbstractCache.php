<?php

namespace Azertype\Cache;

abstract class AbstractCache{

    abstract public function read() : ?array;
    abstract public function store(?array $data) : bool;
    abstract public function clear() : bool;
}
