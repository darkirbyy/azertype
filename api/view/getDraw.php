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
    $game = new Game($db, $cache);

    $nextDraw = $game->getLastDraw();
    if(!isset($nextDraw) || time() >= $nextDraw['validity'])
    {
        $generator = ($_ENV['APP_ENV'] === "prod") ? new HeroGenerator() :  new FakeGenerator();
        $timer = new Timer( $_ENV['TIME_RESET'],  $_ENV['TIME_INTERVAL']);
        $game->generateDraw($generator, $timer);
        $nextDraw = $game->getLastDraw();
    }

    http_response_code(200);
    echo $game->formatDraw($nextDraw);
} 
catch(Throwable $e){
    http_response_code(500);
    if($_ENV['APP_ENV'] === "dev")
        echo json_encode(array('error' => $e->getMessage()));
    return;
}


