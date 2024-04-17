<?php

use Azertype\Helper\DbHandler;
use Azertype\Cache\FileCache as Cache;
use Azertype\Generator\FakeGenerator;
use Azertype\Generator\HeroGenerator;
use Azertype\Helper\Timer;
use Azertype\Game;


try{
    $db = new DbHandler();
    $cache = new Cache('lastDraw');
    $generator = $_ENV['APP_ENV'] === "prod" ? new HeroGenerator() :  new FakeGenerator();
    $timer = new Timer();
    $game = new Game($db, $cache);

    $nextDraw = $game->getLastDraw();
    if(!isset($nextDraw) || !$timer->inSameInterval($nextDraw['timestamp'],time()))
    {
        $game->generateDraw($generator);
        $nextDraw = $game->getLastDraw();
    }

    http_response_code(200);
    echo $game->formatDraw($nextDraw, $timer);
} 
catch(Throwable $e){
    http_response_code(500);
    if($_ENV['APP_ENV'] === "dev")
        echo json_encode(array('error' => $e->getMessage()));
    //error_log($e->getMessage(), 3, $_ENV['APP_ROOT'].'php_errors.log');
    return;
}


