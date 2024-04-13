<?php
// $interval = 10;
// date_default_timezone_set("Europe/Paris");
// $diff = time() - strtotime(date("Y-m-d"));
// $diff -= $diff % $interval;
// echo $diff ."<br>";
// echo "<br>";

// time();
// die();

require_once '../vendor/autoload.php';
require_once '../src/Config.php';

use Azertype\CacheArray;
use Azertype\Config;
use Azertype\DbHandler;

die();

header("Content-Type: text/html");
header("Access-Control-Allow-Origin: *");

try{
    /*
    Retrieve the last game from cache if exists
    or from last database entry otherwise
    */
    $cache = new CacheArray('lastGame');
    $lastGame = $cache->read();
    if(!isset($lastGame)) {
        $db = new DbHandler();
        $lastGame = $db->getLastGame();
        $cache->store($lastGame);
    }
    if(!isset($lastGame))
    {
        http_response_code(404);
        return;
    }
        
    // Compare the timestamp to the last valid one

    http_response_code(200);
    echo $lastGame['words'];
} catch(Throwable $e){
    http_response_code(500);
    echo (string)$e;

    return;
}


