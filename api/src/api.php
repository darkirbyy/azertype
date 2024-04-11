<?php
require_once 'DbHandler.php';

header("Content-Type: text/html");
header("Access-Control-Allow-Origin: *");

$db = new DbHandler();
$lastGame = $db->getLastGame();


if(isset($lastGame))
    echo $lastGame['game_id'];
else
    echo "gneu";

