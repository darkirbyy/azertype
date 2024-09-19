<?php

declare(strict_types=1);

namespace Azertype\Controller;

use PHPUnit\Framework\TestCase;
use Azertype\Helper\DbHandler;
use Azertype\Cache\ApcuCache;
use Azertype\Cache\FileCache;
use Azertype\Handler\DrawHandler;
use Azertype\Helper\Timer;
use Azertype\Controller\DrawController;


final class DrawIntegrationTest extends TestCase
{
    static private $mainDb;
    private $cacheDraw;
    private $cacheScore;
    private $drawHandler;
    private $timer;
    private $generator;
    private $drawController;

    public static function setUpBeforeClass(): void
    {
        self::$mainDb = new DbHandler('test-inte');
    }

    public static function tearDownAfterClass(): void
    {
        unset(self::$mainDb->pdo);
        if(file_exists($_ENV['REL_ROOT'].$_ENV['CACHEFILE_DIR']. 'lastDraw.json'))
            unlink($_ENV['REL_ROOT'].$_ENV['CACHEFILE_DIR']. 'lastDraw.json');
        if(file_exists($_ENV['REL_ROOT'].$_ENV['CACHEFILE_DIR']. 'lastScore.json'))
            unlink($_ENV['REL_ROOT'].$_ENV['CACHEFILE_DIR']. 'lastScore.json');
        rmdir($_ENV['REL_ROOT'].$_ENV['CACHEFILE_DIR']);
        unlink($_ENV['REL_ROOT'].$_ENV['DATABASE_DIR']. 'test-inte.db');
    }


    public function setUp(): void
    {
        $this->cacheDraw = new ApcuCache('lastDraw');
        $this->cacheScore = new FileCache('lastScore');
        $this->drawHandler = new DrawHandler(self::$mainDb, $this->cacheDraw, $this->cacheScore);

        $this->timer = new Timer($_ENV['TIME_RESET'],  $_ENV['TIME_INTERVAL']);
        $this->generator = new ('Azertype\Generator\\' . $_ENV['GENERATOR_NAME'] . 'Generator')();
        $this->drawController = new DrawController($this->drawHandler, $this->timer, $this->generator);
    }

    public function tearDown(): void {
    }

    public function testGetSameTwoDraws(): void
    {
        $result = json_decode($this->drawController->getDraw(), true);
        $result2 = json_decode($this->drawController->getDraw(), true);
        $this->assertEquals($result, $result2);
        $this->assertIsInt($result['game_id']);
        $this->assertIsString($result['words']);
        $this->assertIsInt($result['wait_time']);
        unset(self::$mainDb->pdo);
    }
}
