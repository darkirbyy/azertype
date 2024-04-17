<?php

namespace Azertype\Generator;

/**
 * Generate fake random words for dev and testing purpose
 */ 

class FakeGenerator extends AbstractGenerator{

    /**
     * Generate a list of fake words in format "aaa,ddd,etc"
     * 
     * @param int $size How many words to generate
     * 
     * @return string
     */
    function generate(?int $size = null) : string {
        $size ??= $_ENV['WORDS_PER_DRAW'];
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