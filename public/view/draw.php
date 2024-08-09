<?php

use Azertype\Helper\DbHandler;
use Azertype\Cache\FileCache as Cache;
use Azertype\Handler\DrawHandler;
use Azertype\Controller\DrawController;
use Azertype\Helper\Timer;

$db = new DbHandler();
$cache = new Cache('lastDraw');
$drawHandler = new DrawHandler($db, $cache);

$timer = new Timer($_ENV['TIME_RESET'],  $_ENV['TIME_INTERVAL']);
$generator = new ('Azertype\Generator\\' . $_ENV['GENERATOR_NAME'] . 'Generator')();
$drawController = new DrawController($drawHandler, $timer, $generator);


switch($verb){
    case "GET":
        http_response_code(200);
        echo $drawController->getDraw();
        break;
}
