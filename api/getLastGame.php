<?php
require_once '../vendor/autoload.php';

use Azertype\CacheInFile as Cache;
use Azertype\Config;
use Azertype\DbHandler;
use Azertype\TimeInterval;

header("Content-Type: text/html");
header("Access-Control-Allow-Origin: *");

try{
    /*
    Retrieve the last game from cache if exists
    or from last database entry otherwise
    */
    $cache = new Cache('lastGame');
    $lastGame = $cache->read();
    if(!isset($lastGame)) {
        $db = new DbHandler();
        $lastGame = $db->getLastGame();
        $cache->store($lastGame);
    }

    /*
    Return 404 if neither the cache nor the db have
    proper variables
    */
    if(!isset($lastGame)
     || !key_exists('game_id', $lastGame)
     || !key_exists('creation_timestamp', $lastGame)
     || !key_exists('words', $lastGame))
    {
        http_response_code(404);
        return;
    }
        
    /*
    Compare the current timestamp to the lastGame one
    if not in same interval, generate new game
    */
    if(TimeInterval::areInSameInterval(
        TimeInterval::getCurrentTimestamp(), $lastGame['creation_timestamp']))
        echo 'oui';
    else
        echo 'non';


    http_response_code(200);
    echo $lastGame['words'];
} catch(Throwable $e){
    http_response_code(500);
    echo (string)$e;

    return;
}


