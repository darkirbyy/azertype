<?php
require_once '../vendor/autoload.php';

use Azertype\Game;
use Azertype\Config;
use Azertype\Helper\DbHandler as Db;
use Azertype\Cache\CacheInFile as Cache;
use Azertype\Generator\FakeGenerator as Generator;
use Azertype\Helper\TimeInterval as Interval;

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

try{
    $db = new Db(Config::ROOT.Config::DB_DIRECTORY.Config::DB_FILENAME);
    $cache = new Cache(Config::ROOT.Config::FILECACHE_DIRNAME, 'lastDraw.json');
    $generator = new Generator();
    $game = new Game($db, $cache, $generator);

    $nextDraw = $game->getLastDraw();

    $currentTimestamp = Interval::currentTimestamp();
    if(!isset($nextDraw) || 
       !Interval::areInSameInterval($nextDraw['timestamp'],$currentTimestamp))
    {
        $game->generateDraw($currentTimestamp);
        $nextDraw = $game->getLastDraw();
    }

    http_response_code(200);
    echo  $game->formatDraw($nextDraw, $currentTimestamp);

} catch(Throwable $e){
    http_response_code(500);
    echo (string)$e;
    return;
}


