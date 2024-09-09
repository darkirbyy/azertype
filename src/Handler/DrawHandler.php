<?php

namespace Azertype\Handler;

use Azertype\Helper\DbHandler;
use Azertype\Cache\AbstractCache;
use Azertype\Generator\AbstractGenerator;
use Azertype\Helper\Timer;
use Exception;

class DrawHandler
{
    private DbHandler $mainDb;
    private AbstractCache $cache;

    function __construct(DbHandler $mainDb, AbstractCache $cache)
    {
        $this->mainDb = $mainDb;
        $this->cache = $cache;
    }

    /**
     * Create the table if necessary
     * 
     */
    function createTable(): void
    {
        $this->mainDb->writeQuery(" CREATE TABLE IF NOT EXISTS draws (
                            game_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                            validity INTEGER NOT NULL,
                            words TEXT) ");
    }


    /**
     * Retrieve the last draw from cache if exists and have correct keys, 
     * or from last database entry otherwise if exists, 
     * or null otherwise
     *   
     * @return array 
     */
    function readLastDraw(): ?array
    {
        $lastDraw = $this->cache->read();
        if (!isset($lastDraw)) {
            $this->createTable();
            $queryResult = $this->mainDb->readQuery(
                "SELECT * FROM draws ORDER BY game_id DESC LIMIT 1"
            );
            if($queryResult !== null && isset($queryResult[0])){
                $lastDraw = $queryResult[0];
                $this->cache->store($lastDraw);
            }
        }
        return $lastDraw;
    }

    /**
     * Delete the cache, generate a new set of words and
     * add a new entry into the database 
     * 
     * @param array $data array containing in order (validity, words)
     * 
     * @return bool true if the insertion succeed
     */
    function writeOneDraw(array $data): bool
    {
        $this->cache->clear();
        $this->createTable();
        return (bool) $this->mainDb->writeQuery("INSERT INTO draws (validity, words)
            VALUES (:validity, :words)", $data);
    }

    /**
     * Format a draw into a complete json for front-end
     * 
     * @param array $draw a draw array containing (game_id, validity, words)
     * 
     * @return string
     */
    function formatDraw(array $draw): string
    {
        if (!isset($draw['validity']))
            throw new Exception("DrawHandler unable to format the draw into json");
        $draw['wait_time'] = $draw['validity'] - time();
        unset($draw['validity']);
        return json_encode($draw);
    }
}
