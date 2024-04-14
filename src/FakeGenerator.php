<?php

namespace Azertype;

class FakeGenerator{

    function generate(int $size = Config::WORDS_PER_GAME) : ?string {
        $words = "";
        $char = "a";
        for($i = 0; $i < $size ; $i++){
            $words .= $char.$char.$char.",";
            $char = chr(ord($char) + 1);
        }
        return substr($words,0,strlen($words)-1);
    }

}