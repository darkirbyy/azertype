<?php

use Azertype\Helper\DbHandler;
use Azertype\Cache\ApcuCache;
use Azertype\Cache\FileCache;
use Azertype\Helper\GameHandler;
use Azertype\Controller\DrawController;
use Azertype\Controller\ScoreController;

$mainDb = new DbHandler('main');
$cacheDraw = new ('Azertype\Cache\\' . $_ENV['CACHE_TYPE'] . 'Cache')('lastDraw');
$cacheScore = new ('Azertype\Cache\\' . $_ENV['CACHE_TYPE'] . 'Cache')('lastScore');
$gameHandler = new GameHandler($mainDb, $cacheDraw, $cacheScore);

$scoreController = new ScoreController($gameHandler);


switch($verb){
    case "GET":
        http_response_code(200);
        echo $scoreController->getScore();
        break;

    case "POST":
        http_response_code(200);
        echo "lol";
        break;

    default:
        http_response_code(404);
}
