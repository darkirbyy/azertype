<?php

use Azertype\Helper\DbHandler;
use Azertype\Cache\FileCache as Cache;
use Azertype\Helper\Timer;
use Azertype\Controller\DrawController;

try{
    $db = new DbHandler();
    $cache = new Cache('lastDraw');
    $drawController = new DrawController($db, $cache);

    $draw = $drawController->readLastDraw();
    if(!isset($draw) || time() >= $draw['validity'])
    {
        $generator = new ('Azertype\Generator\\'.$_ENV['GENERATOR_NAME'].'Generator')();
        $timer = new Timer( $_ENV['TIME_RESET'],  $_ENV['TIME_INTERVAL']);
        $words = $generator->generate($_ENV['WORDS_PER_DRAW']);
        $validity = $timer->ceilTimestamp(time());
        $drawController->writeOneDraw(array($validity, $words));
        $draw = $drawController->readLastDraw();
    }

    $json = $drawController->formatDraw($draw);
    http_response_code(200);
    echo $json;
} 
catch(Throwable $e){
    http_response_code(500);
    echo ($_ENV['APP_ENV'] === "dev") ? json_encode(array('error' => $e->getMessage())) : '';
    return;
}


