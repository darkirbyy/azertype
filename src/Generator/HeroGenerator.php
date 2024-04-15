<?php

namespace Azertype\Generator;
use Azertype\Config;

class HeroGenerator extends AbstractGenerator{

    private \CurlHandle $ch;
    private string $baseUrl;

    function __construct(string $baseUrl) {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_HEADER, 0);
        curl_setopt($this->ch, CURLOPT_TIMEOUT,Config::EXTERN_API_TIMEOUT);
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT,Config::EXTERN_API_TIMEOUT);
        $this->baseUrl = $baseUrl;
    }

    function generate(int $size = Config::WORDS_PER_GAME) : string {
        if($size < 1)
            return "";
        curl_setopt($this->ch, CURLOPT_URL, $this->baseUrl.(string)$size);
        $data = curl_exec($this->ch);
        if($data === false)
            throw new \Exception("Generator failed to find words.");
        $data = str_replace(['[', '"', ']'], '',$data);
        return $data;
    }

    function __destruct() {
        curl_close($this->ch);
    }

}