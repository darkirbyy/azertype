<?php

namespace Azertype\Helper;

use Azertype\Helper\DbHandler;
use Azertype\Cache\AbstractCache;
use Exception;

class GameHandler
{
    private DbHandler $mainDb;
    private AbstractCache $cacheDraw;
    private AbstractCache $cacheScore;

    function __construct(DbHandler $mainDb, AbstractCache $cacheDraw, AbstractCache $cacheScore)
    {
        $this->mainDb = $mainDb;
        $this->cacheDraw = $cacheDraw;
        $this->cacheScore = $cacheScore;
    }

    /**
     * Create the table if necessary
     * 
     */
    function createTable(): void
    {
        $this->mainDb->writeQuery(" CREATE TABLE IF NOT EXISTS games (
                            game_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                            validity INTEGER NOT NULL,
                            words TEXT,
                            best_time INTEGER,
                            nb_players INTEGER) ");
    }


    /**
     * Retrieve the last draw from cacheDraw if exists and have correct keys, 
     * or from last database entry otherwise if exists, 
     * or null otherwise
     *   
     * @return array 
     */
    function readLastDraw(): ?array
    {
        $lastDraw = $this->cacheDraw->read();
        if (!isset($lastDraw)) {
            $this->createTable();
            $queryResult = $this->mainDb->readQuery(
                "SELECT game_id, validity, words FROM games ORDER BY game_id DESC LIMIT 1"
            );
            if($queryResult !== null && isset($queryResult[0])){
                $lastDraw = $queryResult[0];
                $this->cacheDraw->store($lastDraw);
            }
        }
        return $lastDraw;
    }

    /**
     * Delete the cacheDraw and cacheScore, generate a new set of words and
     * add a new entry into the database 
     * 
     * @param array $data array containing in order (validity, words)
     * 
     * @return bool true if the insertion succeed
     */
    function writeOneDraw(array $data): bool
    {
        $this->cacheDraw->clear();
        $this->cacheScore->clear();
        $this->createTable();
        return (bool) $this->mainDb->writeQuery("INSERT INTO games (validity, words, best_time, nb_players)
            VALUES (:validity, :words, 0, 0)", $data);
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
        if (!isset($draw['game_id']) || !isset($draw['validity']) || !isset($draw['words']))
            throw new Exception("GameHandler unable to format the draw into JSON");
        $draw['wait_time'] = $draw['validity'] - time();
        unset($draw['validity']);
        return json_encode($draw);
    }

    /**
     * Retrieve the last score from cacheScore if exists and have correct keys, 
     * or from last database entry otherwise if exists, 
     * or null otherwise
     *   
     * @return array 
     */
    function readLastScore(): ?array
    {
        $lastScore = $this->cacheScore->read();
        if (!isset($lastScore)) {
            $this->createTable();
            $queryResult = $this->mainDb->readQuery(
                "SELECT game_id, validity, best_time, nb_players FROM games ORDER BY game_id DESC LIMIT 1"
            );
            if($queryResult !== null && isset($queryResult[0])){
                $lastScore = $queryResult[0];
                $this->cacheScore->store($lastScore);
            }
        }
        return $lastScore;
    }

    /**
     * Delete the cacheScore, update the last score entry with new values
     * 
     * @param array $data array containing in order (best_time, nb_players, game_id)
     * 
     * @return bool true if the insertion succeed
     */
    function updateLastScore(array $data): bool
    {
        $this->cacheScore->clear();
        $this->createTable();
        return (bool) $this->mainDb->writeQuery("UPDATE games 
                      SET best_time=:best_time, nb_players=:nb_players
                      WHERE game_id=:game_id", $data);
    }

    /**
     * Format a score into a complete json for front-end
     * 
     * @param array $score a score array containing (game_id, best_time, nb_players)
     * 
     * @return string
     */
    function formatScore(array $score): string
    {
        unset($score['validity']);
        if (!isset($score['game_id']) || !isset($score['best_time']) || !isset($score['nb_players']))
            throw new Exception("GameHandler unable to format the score into JSON");
        return json_encode($score);
    }
}
