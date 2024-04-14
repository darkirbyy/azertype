<?php

namespace Azertype\Generator;
use Azertype\Config;

class FakeGenerator{

    function generate(int $size = Config::WORDS_PER_GAME) : string {
        $words = "";
        for($i = 0; $i < $size ; $i++){
            $char = chr(ord('a') + rand(0,25));
            $words .= $char.$char.$char.",";
        }
        return substr($words,0,strlen($words)-1);
    }

}