<?php

declare(strict_types=1);

namespace Azertype\Helper;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Faker\Factory;
use PDOException;

#[CoversClass(DbHandler::class)]
final class DbHandlerTest extends TestCase
{
    private DbHandler $dbHandler;
    private static $faker;

    public static function setUpBeforeClass(): void
    {
        self::$faker = Factory::create('fr_FR');
    }

    public function setUp(): void
    {
        $this->dbHandler = new DbHandler('test');
        $this->dbHandler->pdo->beginTransaction();
    }

    public function tearDown(): void
    {
        $this->dbHandler->pdo->rollBack();
    }

    public function testCreateDatabaseFolder(): void
    {
        $this->dbHandler->pdo->rollBack();
        $old_env_value = $_ENV['DATABASE_DIR'];
        $_ENV['DATABASE_DIR'] = 'tests/other_database/';
        $filePath = $_ENV['REL_ROOT'] . $_ENV['DATABASE_DIR'] . 'test.db';

        $this->dbHandler = new DbHandler('test');
        $this->dbHandler->pdo->beginTransaction();
        $this->assertFileExists($filePath);

        if (file_exists($filePath))
            unlink($filePath);
        rmdir($_ENV['REL_ROOT'] . $_ENV['DATABASE_DIR']);
        $_ENV['DATABASE_DIR'] = $old_env_value;
    }

    public function testGoodWriteOperation(): void
    {
        $rowCount = $this->dbHandler->writeQuery(
            "INSERT INTO games (validity,words,best_time,nb_players) VALUES (:validity, :words,0,0)",
            [self::$faker->unixTime(), "'" . self::$faker->words(5, true) . "'"]
        );
        $this->assertEquals(1, $rowCount);
    }

    public function testWrongWriteOperation(): void
    {
        $this->expectException(PDOException::class);
        $rowCount = $this->dbHandler->writeQuery(
            "INSERT INTO notatable (timestamp)
             VALUES (:validity)",
            [self::$faker->unixTime()]
        );
    }

    public function testBasicReadOperation(): void
    {
        $data = $this->dbHandler->readQuery(
            "SELECT * FROM games"
        );
        $this->assertEquals(3, sizeof($data));
    }

    public function testComplexReadOperation(): void
    {
        $data = $this->dbHandler->readQuery(
            "SELECT * FROM games ORDER BY game_id DESC LIMIT :limit",
            [1]
        );
        $this->assertEquals(sizeof($data), 1);
        $this->assertEquals(3, $data[0]['game_id']);
    }

    public function testEmptyReadOperation(): void
    {
        $data = $this->dbHandler->readQuery(
            "SELECT * FROM games WHERE game_id = :id",
            [-1]
        );
        $this->assertEquals($data, array());
    }

    public function testWrongReadOperation(): void
    {
        $this->expectException(PDOException::class);
        $data = $this->dbHandler->readQuery(
            "SELECT * FROM notatable"
        );
    }
}
