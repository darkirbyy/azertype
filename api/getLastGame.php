<?php
require_once '../vendor/autoload.php';

use Azertype\CacheInFile as Cache;
use Azertype\Config;
use Azertype\DbHandler;
use Azertype\TimeInterval;
use Azertype\FakeGenerator as Generator;

header("Content-Type: text/html");
header("Access-Control-Allow-Origin: *");

try{
    /*
    Retrieve the last game from cache if exists and 
    have correct keys, or from last database entry otherwise
    */
    $cache = new Cache('lastGame');
    $lastGame = $cache->read();
    if (!isset($lastGame) ||
        0 !== count(array_diff(['game_id', 'timestamp', 'words'],
                    array_keys($lastGame)))){
        $db = new DbHandler();
        $lastGame = $db->getLastGame();
    }

    /*
    If there is not game in db, or the game is too old,
    generate a new one
    */
    $currentGame = $lastGame;
    $currentTimestamp = TimeInterval::getCurrentTimestamp();
    if(!isset($currentGame) ||
    !TimeInterval::areInSameInterval($currentGame['timestamp'],$currentTimestamp))
    {
        $generator = new Generator();
        $words = $generator->generate(5);
        if(!isset($words))
            throw new Exception("Word generator does not respond");
        $currentGame['words'] = $words;
        $currentGame['timestamp'] =  $currentTimestamp;
        $db = new DbHandler();
        $db->addNewGame($currentTimestamp, $words);
    }

    if($currentGame !== $lastGame)
        $cache->store($currentGame);

    http_response_code(200);
    echo  $currentGame['words'];
} catch(Throwable $e){
    http_response_code(500);
    echo (string)$e;

    return;
}


