<?php

declare(strict_types=1);

namespace Azertype\Controller;

use PHPUnit\Framework\TestCase;
use Azertype\Helper\DbHandler;
use Azertype\Cache\ApcuCache;
use Azertype\Handler\DrawHandler;
use Azertype\Helper\Timer;
use Azertype\Controller\DrawController;


final class DrawIntegrationTest extends TestCase
{
    private $db;
    private $cache;
    private $drawHandler;
    private $timer;
    private $generator;
    private $drawController;

    public static function setUpBeforeClass(): void
    {

    }

    public static function tearDownAfterClass(): void
    {
        unlink($_ENV['REL_ROOT'].$_ENV['CACHEFILE_DIR']. 'lastDraw.json');
        rmdir($_ENV['REL_ROOT'].$_ENV['CACHEFILE_DIR']);
    }


    public function setUp(): void
    {
        $this->db = new DbHandler();
        $this->db->pdo->beginTransaction();

        $this->cache = new ApcuCache('lastDraw');
        $this->drawHandler = new DrawHandler($this->db, $this->cache);

        $this->timer = new Timer($_ENV['TIME_RESET'],  $_ENV['TIME_INTERVAL']);
        $this->generator = new ('Azertype\Generator\\' . $_ENV['GENERATOR_NAME'] . 'Generator')();
        $this->drawController = new DrawController($this->drawHandler, $this->timer, $this->generator);
    }

    public function tearDown(): void {
        $this->db->pdo->rollBack();
    }

    public function testGetSameTwoDraws(): void
    {
        $result = json_decode($this->drawController->getDraw(), true);
        $result2 = json_decode($this->drawController->getDraw(), true);
        $this->assertEquals($result, $result2);
        $this->assertEquals(195, $result['game_id']);
        $this->assertIsString($result['words']);
        $this->assertIsInt($result['wait_time']);
    }
}
