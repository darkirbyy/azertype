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
        exec('sqlite3 database/tests.sqlite ".dump" > DbFixture.sql'); 
        self::$faker = Factory::create();
    }

    public static function tearDownAfterClass(): void
    {   
        if(file_exists("DbFixture.sql"))
            unlink("DbFixture.sql");
    }
        
    public function setUp():void
    {
        $this->dbHandler = new DbHandler(":memory:");
        $this->dbHandler->exec("DbFixture.sql");
    }

    public function testGoodWriteOperation():void{
        $rowCount = $this->dbHandler->writeQuery(
            "INSERT INTO games (timestamp,words)
             VALUES(".self::$faker->unixTime().", '".self::$faker->words(5, true)."')");
        $this->assertEquals(1, $rowCount);
    }

    public function testBadBadOperation():void{
        $this->expectException(PDOException::class);
        $rowCount = $this->dbHandler->writeQuery(
            "INSERT INTO notatable (timestamp)
             VALUES(".self::$faker->unixTime().")");
    }

    public function testBasicReadOperation():void{
        $data = $this->dbHandler->readQuery(
            "SELECT * FROM games");
        $this->assertEquals(25, sizeof($data));
    }

    public function testComplexReadOperation():void{
        $data = $this->dbHandler->readQuery(
            "SELECT * FROM games ORDER BY game_id DESC LIMIT 1");
        $this->assertEquals(sizeof($data), 1);
        $this->assertEquals(34, $data[0]['game_id']);
    }

    public function testEmptyReadOperation():void{
        $data = $this->dbHandler->readQuery(
            "SELECT * FROM games WHERE game_id = -1");
        $this->assertEquals($data, array());
    }

    public function testWrongReadOperation():void{
        $this->expectException(PDOException::class);
        $data = $this->dbHandler->readQuery(
            "SELECT * FROM notatabl");
    }




   

}
