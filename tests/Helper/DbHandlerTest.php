<?php declare(strict_types=1);

namespace Azertype\Helper;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Azertype\Config;

#[CoversClass(DbHandler::class)]
#[UsesClass(Config::class)]
final class DbHandlerTest extends TestCase
{
    private DbHandler $dbHandler;
    
    public function setUp():void
    {
        $this->dbHandler = new DbHandler(Config::FIXTURE.'test.sqlite');
        $this->dbHandler->getPDO()->beginTransaction();
    }
    
    public function tearDown():void
    {
        $this->dbHandler->getPDO()->rollBack();
    }

    public function testWriteOperation():void{
        $rowCount = $this->dbHandler->writeQuery(
            "INSERT INTO games (timestamp,words)
             VALUES(156448, 'some,words')");
        $this->assertEquals($rowCount, 1);
    }

    public function testBasicReadOperation():void{
        $data = $this->dbHandler->readQuery(
            "SELECT * FROM games");
        $this->assertEquals(sizeof($data), 25);
    }

    public function testComlexReadOperation():void{
        $data = $this->dbHandler->readQuery(
            "SELECT * FROM games ORDER BY game_id DESC LIMIT 1");
        $this->assertEquals(sizeof($data), 1);
        $this->assertEquals($data[0]['game_id'], 34);
    }



   

}
