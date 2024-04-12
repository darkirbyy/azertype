<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/src/DbHandler.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/src/Cache.php';

header("Content-Type: text/html");
header("Access-Control-Allow-Origin: *");

try{
    if(file_exists($cache_path) && ($cache = file_get_contents($cache_path)) !== false && strlen($cache) > 0)
        echo $cache;
    else {
        $db = new DbHandler();
        $lastGame = $db->getLastGame();
        //echo json_encode($lastGame);
        echo $lastGame['words'];
    }
} catch(Throwable $e){
   //echo json_encode(['error'=>$e->getMessage()]);
   echo 'error';
}


