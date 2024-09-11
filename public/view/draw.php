<?php

use Azertype\Helper\DbHandler;
use Azertype\Cache\ApcuCache;
use Azertype\Handler\DrawHandler;
use Azertype\Controller\DrawController;
use Azertype\Generator\FakeGenerator;
use Azertype\Generator\HeroGenerator;
use Azertype\Generator\SelfGenerator;
use Azertype\Helper\Timer;

$mainDb = new DbHandler('main');
$cache = new ApcuCache('lastDraw');
$drawHandler = new DrawHandler($mainDb, $cache);

$timer = new Timer($_ENV['TIME_RESET'],  $_ENV['TIME_INTERVAL']);
$generator = new ('Azertype\Generator\\' . $_ENV['GENERATOR_NAME'] . 'Generator')();
if($generator instanceof SelfGenerator){
    $wordsDb = new DbHandler('words');
    $generator->initialize($wordsDb, true);
}

$drawController = new DrawController($drawHandler, $timer, $generator);


switch($verb){
    case "GET":
        http_response_code(200);
        echo $drawController->getDraw();
        break;
}
