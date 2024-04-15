<?php

namespace Azertype\Generator;

class HeroGenerator extends AbstractGenerator{

    private \CurlHandle $ch;

    function __construct() {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_HEADER, 0);
        curl_setopt($this->ch, CURLOPT_TIMEOUT,$_ENV['GENERATOR_HERO_TIMEOUT']);
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT,$_ENV['GENERATOR_HERO_TIMEOUT']);
    }

    function generate(?int $size = null) : string {
        $size ??= $_ENV['GENERATOR_DEFAULTNBWORDS'];
        if($size < 1)
            return "";

        curl_setopt($this->ch, CURLOPT_URL, $_ENV['GENERATOR_HERO_URL'].(string)$size);
        $data = curl_exec($this->ch);
        $httpcode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        if($data === false || $httpcode < 200 || $httpcode >= 300)
            throw new \Exception("HeroGenerator failed to get words from api.");
        $data = json_decode($data);
        if(!isset($data))
            throw new \Exception("HeroGenerator failed to parse words.");
        $words = implode(',', $data);
        return $words;
    }

    function __destruct() {
        curl_close($this->ch);
    }

}