<?php
require_once '../vendor/autoload.php';


use Azertype\Helper\DbHandler;
use Azertype\Cache\FileCache as Cache;
use Azertype\Generator\FakeGenerator;
use Azertype\Generator\HeroGenerator;
use Azertype\Helper\Timer;
use Azertype\Game;
use Faker\Factory;

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

try{
    $db = new DbHandler();
    $cache = new Cache('lastDraw');
    $generator = $_ENV['APP_ENV'] === "prod" ? new HeroGenerator() :  new FakeGenerator();
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
    if($_ENV['APP_ENV'] === "dev")
        echo json_encode(array('error' => $e->getMessage()));
    error_log($e->getMessage(), 3, $_ENV['APP_ROOT'].'php_errors.log');
    return;
}


