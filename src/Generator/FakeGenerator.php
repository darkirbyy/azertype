<?php

namespace Azertype\Generator;

class FakeGenerator extends AbstractGenerator{

    function generate(?int $size = null) : string {
        $size ??= $_ENV['GENERATOR_DEFAULTNBWORDS'];
        if($size < 1)
            return "";

        $words = "";
        for($i = 0; $i < $size ; $i++){
            $char = chr(ord('a') + rand(0,25));
            $words .= $char.$char.$char.",";
        }
        return substr($words,0,strlen($words)-1);
    }

}