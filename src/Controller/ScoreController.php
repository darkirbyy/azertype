<?php

namespace Azertype\Controller;

use Azertype\Generator\AbstractGenerator;
use Azertype\Helper\Timer;
use Azertype\Helper\GameHandler;

class ScoreController
{
    private GameHandler $gameHandler;

    function __construct(GameHandler $gameHandler)
    {
        $this->gameHandler = $gameHandler;
    }

    function getScore(): ?string
    {
        $score = $this->gameHandler->readLastScore();
        if (!isset($score) || time() >= $score['validity']) {
            $score['game_id'] = -1;
        }
        $json = $this->gameHandler->formatScore($score);
        return $json;
    }
}
