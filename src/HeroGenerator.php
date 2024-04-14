<?php

namespace Azertype;

class HeroGenerator{

    private $ch;

    function __construct() {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_HEADER, 0);
    }

    function generate(int $size) : ?string {
        curl_setopt($this->ch, CURLOPT_URL, Config::HERO_URL.(string)$size);
        $data = curl_exec($this->ch);
        if($data === false)
            return null;
        //$data = '["salifiâmes","débouchaient","corrigeriez","atrophiaient","réaniment"]';
        $data = str_replace(['[', '"', ']'], '',$data);
        //$array= explode(',', $data);
        return $data;
    }

    function __destruct() {
        curl_close($this->ch);
    }

}