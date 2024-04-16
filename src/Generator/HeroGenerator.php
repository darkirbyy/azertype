<?php

namespace Azertype\Generator;

class HeroGenerator extends AbstractGenerator{

    function generate(?int $size = null) : string {
        $size ??= $_ENV['GENERATOR_DEFAULTNBWORDS'];
        if($size < 1)
            return "";
        $data = $this->httpRequest($size);
        if(!preg_match('/^\[("[a-zéèçàù-]{0,}",){0,}"[a-zéèçàù-]{0,}"]$/iu', $data))
            throw new \Exception("HeroGenerator failed to parse words.");
        $words = implode(',', json_decode($data));
        return $words;
    }

    function httpRequest(int $size) : string{
        $curlHandle = curl_init();
        curl_setopt($curlHandle,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curlHandle,CURLOPT_HEADER,0);
        curl_setopt($curlHandle,CURLOPT_URL,$_ENV['GENERATOR_HERO_URL'].(string)$size);
        curl_setopt($curlHandle,CURLOPT_TIMEOUT_MS,$_ENV['GENERATOR_HERO_TIMEOUT']*2);
        curl_setopt($curlHandle,CURLOPT_CONNECTTIMEOUT_MS,$_ENV['GENERATOR_HERO_TIMEOUT']);
        $data = curl_exec($curlHandle);
        $httpcode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
        curl_close($curlHandle);

        if($data === false || $httpcode < 200 || $httpcode >= 300)
            throw new \Exception("HeroGenerator failed to connect to api.");
        return $data;
    }
}