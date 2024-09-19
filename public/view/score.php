<?php

use Azertype\Helper\DbHandler;
use Azertype\Cache\ApcuCache;
use Azertype\Helper\GameHandler;
use Azertype\Controller\DrawController;
use Azertype\Controller\ScoreController;

$mainDb = new DbHandler('main');
$cacheDraw = new ApcuCache('lastDraw');
$cacheScore = new ApcuCache('lastScore');
$gameHandler = new GameHandler($mainDb, $cacheDraw, $cacheScore);

$scoreController = new ScoreController($gameHandler);


switch($verb){
    case "GET":
        http_response_code(200);
        echo $scoreController->getScore();
        break;
}
