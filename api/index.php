<?php
require_once '../vendor/autoload.php';
date_default_timezone_set($_ENV['TIME_ZONE']);

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$request = $_SERVER['REQUEST_URI'];
switch ($request) {
    /*case '':
    case '/':
        require __DIR__ . $viewDir . 'home.php';
        break;*/

    case '/getDraw':
        require 'getDraw.php';
        break;

    default:
        http_response_code(404);
        //require __DIR__ . $viewDir . '404.php';
}
