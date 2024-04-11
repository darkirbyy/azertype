<?php

class DbHandler{
    const DB_PATH = "/db/db1.sqlite";
    protected $pdo;

    function __construct(){
        $this->pdo = new PDO("sqlite:".$_SERVER['DOCUMENT_ROOT'].DbHandler::DB_PATH);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    function createTables(): void{
        $this->pdo->query(" CREATE TABLE IF NOT EXISTS games (
                            game_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                            datetime DATETIME,
                            words TEXT)
                        ");
    }

    function getLastGame() : ?array {
        $this->createTables();
        $stmt = $this->pdo->prepare("SELECT * FROM games ORDER BY game_id DESC LIMIT 1");
        $stmt->execute();
        $lastGame = $stmt->fetch();
        return $lastGame === false ? null : $lastGame;
    }
}