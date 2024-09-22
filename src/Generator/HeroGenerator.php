<?php

namespace Azertype\Generator;

use Exception;
/**
 * Generate words from extern api named hero
 */ 

class HeroGenerator extends AbstractGenerator{

    const HERO_URL = "https://random-word-api.herokuapp.com/word?lang=fr&number=";

    /**
     * Reformat the words given by an http request into "word,word,etc" 
     * 
     * @param int $size How many words to generate
     * 
     * @throws Exception If the body of the http request is not correctly formatted 
     * @return string
     */
    function generate(int $size) : string {
        if($size < 1)
            return "";
        $data = $this->httpRequest($size);
        if(!preg_match('/^\[("[a-zéèçàù-]{0,}",){0,}"[a-zéèçàù-]{0,}"]$/iu', $data))
            throw new Exception("HeroGenerator failed to parse words : ".$data);
        $words = implode(',', json_decode($data));
        return $words;
    }

    /**
     * Make an http request to hero api to get words
     * 
     * @param int $size How many words to ask to the api
     * 
     * @throws Exception If the request fail
     * @return string
     */
    function httpRequest(int $size) : string{
        $curlHandle = curl_init();
        curl_setopt($curlHandle,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curlHandle,CURLOPT_HEADER,0);
        curl_setopt($curlHandle,CURLOPT_URL,self::HERO_URL.(string)$size);
        curl_setopt($curlHandle,CURLOPT_TIMEOUT_MS,$_ENV['HERO_CURL_TIMEOUT_MS']*2);
        curl_setopt($curlHandle,CURLOPT_CONNECTTIMEOUT_MS,$_ENV['HERO_CURL_TIMEOUT_MS']);
        $data = curl_exec($curlHandle);
        $httpcode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
        curl_close($curlHandle);

        if($data === false || $httpcode < 200 || $httpcode >= 300)
            throw new Exception("HeroGenerator failed to connect to api.");
        return $data;
    }
}