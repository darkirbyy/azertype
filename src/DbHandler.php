<?php

namespace Azertype;
use PDO;

class DbHandler{
    protected PDO $pdo;

    function __construct(){
        $dbFilePath = Config::ROOT.Config::DB_DIRECTORY.Config::DB_FILENAME;
        $this->pdo = new PDO("sqlite:".$dbFilePath);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    function createTables(): void{
        $this->pdo->query(" CREATE TABLE IF NOT EXISTS games (
                            game_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                            creation_time TIMESTAMP,
                            words TEXT) ");
    }

    function getLastGame() : ?array {
        $this->createTables();
        $stmt = $this->pdo->prepare("SELECT * FROM games ORDER BY game_id DESC LIMIT 1");
        $stmt->execute();
        $lastGame = $stmt->fetch();
        return $lastGame !== false ? $lastGame : null;
    }
}