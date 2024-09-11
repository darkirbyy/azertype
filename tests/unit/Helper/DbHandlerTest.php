<?php declare(strict_types=1);

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
        
    public function setUp():void
    {
        $this->dbHandler = new DbHandler('test');
        $this->dbHandler->pdo->beginTransaction();
    }

    public function tearDown():void{
        $this->dbHandler->pdo->rollBack();
    }

    public function testGoodWriteOperation():void{
        $rowCount = $this->dbHandler->writeQuery(
            "INSERT INTO draws (validity,words) VALUES (:validity, :words)", 
            [self::$faker->unixTime(), "'".self::$faker->words(5, true)."'"]);
        $this->assertEquals(1, $rowCount);
    }

    public function testWrongWriteOperation():void{
        $this->expectException(PDOException::class);
        $rowCount = $this->dbHandler->writeQuery(
            "INSERT INTO notatable (timestamp)
             VALUES (:validity)", [self::$faker->unixTime()]);
    }

    public function testBasicReadOperation():void{
        $data = $this->dbHandler->readQuery(
            "SELECT * FROM draws");
        $this->assertEquals(194, sizeof($data));
    }

    public function testComplexReadOperation():void{
        $data = $this->dbHandler->readQuery(
            "SELECT * FROM draws ORDER BY game_id DESC LIMIT :limit", [1]);
        $this->assertEquals(sizeof($data), 1);
        $this->assertEquals(194, $data[0]['game_id']);
    }

    public function testEmptyReadOperation():void{
        $data = $this->dbHandler->readQuery(
            "SELECT * FROM draws WHERE game_id = :id", [-1]);
        $this->assertEquals($data, array());
    }

    public function testWrongReadOperation():void{
        $this->expectException(PDOException::class);
        $data = $this->dbHandler->readQuery(
            "SELECT * FROM notatabl");
    }

}
