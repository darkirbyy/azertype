<?php
require_once '../.vendor/autoload.php';
date_default_timezone_set($_ENV['TIME_ZONE'] ?? 'UTC');

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

try {

    $path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    $verb = $_SERVER['REQUEST_METHOD'];

    switch ($path) {
        case '/draw':
            require 'view/draw.php';
            break;

        default:
            http_response_code(404);
    }
    
} catch (Throwable $e) {
    http_response_code(500);
    echo ($_ENV['APP_ENV'] === "dev") ? json_encode(array('error' => $e->getMessage())) : '';
    return;
}
