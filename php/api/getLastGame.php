<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/src/DbHandler.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/src/Cache.php';

header("Content-Type: text/html");
header("Access-Control-Allow-Origin: *");

try{

    // Rerieve the last game from cache if exists
    // or from last database entry otherwise
    $cache = new Cache('lastGame');
    $lastGame = $cache->read();
    if(!isset($lastGame)) {
        $db = new DbHandler();
        $lastGame = $db->getLastGame();
        $cache->store($lastGame);
    }

    // Compare the timestamp to the last valid one

    http_response_code(200);
    echo $lastGame['words'];
} catch(Throwable $e){
    http_response_code(503);
   //echo 'error';
}


