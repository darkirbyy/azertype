<?php

namespace Azertype\Controller;

use Azertype\Helper\DbHandler;
use Azertype\Cache\FileCache as Cache;
use Azertype\Generator\AbstractGenerator;
use Azertype\Helper\Timer;
use Azertype\Handler\DrawHandler;

class DrawController
{
    private DrawHandler $drawHandler;
    private Timer $timer;
    private AbstractGenerator $generator;

    function __construct(DrawHandler $drawHandler, Timer $timer, AbstractGenerator $generator)
    {
        $this->drawHandler = $drawHandler;
        $this->timer = $timer;
        $this->generator = $generator;
    }

    function getDraw(): ?string
    {
        $draw = $this->drawHandler->readLastDraw();
        if (!isset($draw) || time() >= $draw['validity']) {
            $words = $this->generator->generate($_ENV['WORDS_PER_DRAW']);
            $validity = $this->timer->ceilTimestamp(time());
            $this->drawHandler->writeOneDraw(array($validity, $words));
            $draw = $this->drawHandler->readLastDraw();
        }

        $json = $this->drawHandler->formatDraw($draw);
        return $json;
    }
}
