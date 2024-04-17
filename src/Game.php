<?php

namespace Azertype;

use Azertype\Helper\DbHandler;
use Azertype\Cache\AbstractCache;
use Azertype\Generator\AbstractGenerator;
use Azertype\Helper\Timer;

class Game{
    private DbHandler $db; 
    private AbstractCache $cache;

    function __construct(DbHandler $db, AbstractCache $cache){
        $this->db = $db;
        $this->cache = $cache;
    }

    /*
    Create the games table if necessary
    */
    function createTable(): void{
        $this->db->writeQuery(" CREATE TABLE IF NOT EXISTS games (
                            game_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                            validity INTEGER NOT NULL,
                            words TEXT) ");
    }

    /*
    Retrieve the last draw from cache if exists and 
    have correct keys, or from last database entry otherwise
    */
    function getLastDraw() : ?array {
        $lastDraw = $this->cache->read();
        if (!isset($lastDraw)){
            $this->createTable();
            [$lastDraw,] = $this->db->readQuery(
                "SELECT * FROM games ORDER BY game_id DESC LIMIT 1");
        }
        $this->cache->store($lastDraw);
        return $lastDraw;
    }

    /*
    Delete the cache, generate a new set of words and
    add a new entry into the database 
    */
    function generateDraw(AbstractGenerator $generator, Timer $timer) : void {
        $this->cache->clear();
        $words = $generator->generate();
        $validity = $timer->ceilTimestamp(time());
        $this->createTable();
        $this->db->writeQuery("INSERT INTO games (validity, words)
            VALUES (:validity, :words)", array($validity, $words));
    }

    /*
    Format a draw into a complete json for front-end
    */
    function formatDraw(array $draw) : string {
        $draw['wait_time'] = $draw['validity'] - time();
        return json_encode($draw);
    }
}
