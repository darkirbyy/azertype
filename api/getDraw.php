<?php
require_once '../vendor/autoload.php';

use Azertype\Helper\DbHandler as Db;
use Azertype\Cache\FileCache as Cache;
use Azertype\Generator\FakeGenerator as Generator;
use Azertype\Helper\Timer;
use Azertype\Game;

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

try{
    $db = new Db();
    $cache = new Cache($_ENV['CACHE_LASTDRAW']);
    $generator = new Generator();
    $game = new Game($db, $cache, $generator);

    $nextDraw = $game->getLastDraw();
    if(!isset($nextDraw) || 
       !Timer::areInSameInterval($nextDraw['timestamp'],Timer::currentTimestamp()))
    {
        $game->generateDraw();
        $nextDraw = $game->getLastDraw();
    }

    http_response_code(200);
    echo $game->formatDraw($nextDraw);

} catch(Throwable $e){
    http_response_code(500);
    echo json_encode(array('error' => (string)$e));
    return;
}


