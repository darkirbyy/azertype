<?php
require_once '../vendor/autoload.php';
date_default_timezone_set($_ENV['TIME_ZONE'] ?? 'UTC');

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

sleep(3);
$request = $_SERVER['REQUEST_URI'];
switch ($request) {
    case '/getDraw':
        require 'view/getDraw.php';
        break;

    default:
        http_response_code(404);
}
