<?php

class DbHandler{

    const DB_DIRECTORY = "database/";
    const DB_FILENAME = "db1.sqlite";

    protected PDO $pdo;

    function __construct(){
        $dbPath = $_SERVER['DOCUMENT_ROOT']."/".DbHandler::DB_DIRECTORY;
        // if (!is_dir($dbPath)) {
        //     mkdir($dbPath);       
        // } 
        $this->pdo = new PDO("sqlite:".$dbPath.DbHandler::DB_FILENAME);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    function createTables(): void{
        $this->pdo->query(" CREATE TABLE IF NOT EXISTS games (
                            game_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                            creation_time TIMESTAMP,
                            words TEXT) ");
    }

    function getLastGame() : array {
        $this->createTables();
        $stmt = $this->pdo->prepare("SELECT * FROM games ORDER BY game_id DESC LIMIT 1");
        $stmt->execute();
        $lastGame = $stmt->fetch();
        return $lastGame;
    }
}