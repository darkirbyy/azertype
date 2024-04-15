<?php

namespace Azertype\Generator;

abstract class AbstractGenerator{

    abstract function generate(?int $size = null) : string;
}
