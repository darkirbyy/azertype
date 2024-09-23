<?php

namespace Azertype\Controller;

use Azertype\Helper\GameHandler;
use Exception;

class ScoreController
{
    private GameHandler $gameHandler;

    function __construct(GameHandler $gameHandler)
    {
        $this->gameHandler = $gameHandler;
    }

    /**
     * Handle the GET verb on the score endpoint
     * 
     * @return string the last score correctly formatted, or game id = 0 if not exist
     */
    function getScore(): ?string
    {
        $score = $this->gameHandler->readLastScore();
        if (!isset($score) || time() >= $score['validity']) {
            $score['game_id'] = 0;
        }
        $json = $this->gameHandler->formatScore($score);
        return $json;
    }

    /**
     * Handle the POST verb on the score endpoint
     * 
     * @return string the last draw score formatted, or a game id of zero if not exist
     * @throws Exception If the body is not correctly formatted, or the game has expired or the time is invalid
     */
    function postScore(): void
    {
        // Decode the json payload from the body and check that the keys are corrects
        $input = json_decode(file_get_contents("php://input"), true);
        if (!isset($input['game_id']) || !isset($input['time'])) {
            throw new Exception("malformed body", 400);
        }

        // get the last score in cache or in db if exists,
        // check that it's still valid, and corresponds to requested id
        $score = $this->gameHandler->readLastScore();
        if (!isset($score) || time() >= $score['validity'] || $input['game_id'] != $score['game_id']) {
            throw new Exception("game expired", 400);
        }
        
        // calculate new values ; negative time are skipped
        $new_nb_players = $score['nb_players'] + 1;
        if ($input['time'] <= 0) {
            $new_best_time = $score['best_time'];
        }
        else {
            $new_best_time = ($score['best_time'] == 0 || $input['time'] < $score['best_time']) ? $input['time'] : $score['best_time'];
        }
        
        // update database
        $this->gameHandler->updateLastScore(array(
            $new_best_time,
            $new_nb_players,
            $input['game_id']
        ));
    }
}
