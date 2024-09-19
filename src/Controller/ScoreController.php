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
            $this->gameHandler->clearOldScore();
            $score = $this->gameHandler->readLastScore();
        }
        $json = $this->gameHandler->formatScore($score);
        return $json;
    }
}
