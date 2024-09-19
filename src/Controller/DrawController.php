<?php

namespace Azertype\Controller;

use Azertype\Generator\AbstractGenerator;
use Azertype\Helper\Timer;
use Azertype\Helper\GameHandler;

class DrawController
{
    private GameHandler $gameHandler;
    private Timer $timer;
    private AbstractGenerator $generator;

    function __construct(GameHandler $gameHandler, Timer $timer, AbstractGenerator $generator)
    {
        $this->gameHandler = $gameHandler;
        $this->timer = $timer;
        $this->generator = $generator;
    }

    function getDraw(): ?string
    {
        $draw = $this->gameHandler->readLastDraw();
        if (!isset($draw) || time() >= $draw['validity']) {
            $words = $this->generator->generate($_ENV['WORDS_PER_DRAW']);
            $validity = $this->timer->ceilTimestamp(time());
            $this->gameHandler->writeOneDraw(array($validity, $words));
            $draw = $this->gameHandler->readLastDraw();
        }

        $json = $this->gameHandler->formatDraw($draw);
        return $json;
    }
}
