<?php

namespace Azertype;

use Azertype\Helper\DbHandler as Db;
use Azertype\Cache\CacheInFile as Cache;
use Azertype\Generator\FakeGenerator as Generator;
use Azertype\Helper\TimeInterval;
use Exception;

//use Azertype\Config;

class Game{
    private Db $db; 
    private Cache $cache;
    private Generator $generator;

    function __construct(Db $db, Cache $cache, Generator $generator){
        $this->db = $db;
        $this->cache = $cache;
        $this->generator = $generator;
    }

    /*
    Create the games table if necessary
    */
    function createTable(): void{
        $this->db->connect();
        $this->db->writeQuery(" CREATE TABLE IF NOT EXISTS games (
                            game_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                            timestamp INTEGER NOT NULL,
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
            [$lastDraw,] = $this->db->readQuery("SELECT * FROM games ORDER BY game_id DESC LIMIT 1");
        }
        $this->cache->store($lastDraw);
        return $lastDraw;
    }

    /*
    Delete the cache, generate a new set of words and
    add a new entry into the database 
    */
    function generateDraw(int $timestamp) : void {
        $this->cache->clear();
        $words = $this->generator->generate(5);
        $this->createTable();
        $this->db->writeQuery("INSERT INTO games (timestamp, words)
                               VALUES (:timestamp, :words)",
                               array($timestamp, $words));
    }

    /*
    Format a draw into a complete json for front-end
    */
    function formatDraw($draw, $timestamp) : string {
        $draw['wait_time'] = TimeInterval::ceilInterval($draw['timestamp']) - $timestamp;
        return json_encode($draw);
    }
}
