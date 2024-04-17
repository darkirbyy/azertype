<?php

namespace Azertype\Generator;

/**
 * All word generators must extends this class 
 */ 
abstract class AbstractGenerator{

    abstract function generate(?int $size = null) : string;
}
