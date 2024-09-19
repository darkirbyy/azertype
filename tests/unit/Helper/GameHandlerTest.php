<?php declare(strict_types=1);

namespace Azertype\Helper;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use phpmock\mockery\PHPMockery;
use Azertype\Cache\AbstractCache;
use Tests\fixture\CacheFixture;
use Tests\fixture\HandlerFixture;
use Tests\fixture\GeneratorFixture;
use Azertype\Helper\DbHandler;
use Exception;
use Faker\Factory;

#[CoversClass(GameHandler::class)]
final class GameHandlerTest extends TestCase
{
    private $mainDbMock;
    private $cacheDrawMock;
    private $cacheScoreMock;
    private $gameHandler;
    private static $faker;

    public static function setUpBeforeClass(): void
    {
        self::$faker = Factory::create('fr_FR');
    }

    public static function tearDownAfterClass(): void
    {

    }

    public function setUp():void
    {
        $this->mainDbMock = $this->createMock(DbHandler::class);
        $this->cacheDrawMock = $this->createMock(AbstractCache::class);
        $this->cacheScoreMock = $this->createMock(AbstractCache::class);
        $this->gameHandler = new GameHandler($this->mainDbMock, $this->cacheDrawMock, $this->cacheScoreMock);
    }

    public function tearDown():void
    {
        \Mockery::close();
    }

    public function testReadLastDrawGoodCache() : void{
        $this->cacheDrawMock->expects($this->once())
                  ->method('read')
                  ->willReturn(CacheFixture::DRAW_GOOD_ARRAY);

        $this->assertEquals(CacheFixture::DRAW_GOOD_ARRAY, $this->gameHandler->readLastDraw());
    }

    public function testReadLastDrawNoCacheGoodDb() : void{
        $this->cacheDrawMock->expects($this->once())
                        ->method('read')
                        ->willReturn(null);
        $this->mainDbMock->expects($this->once())
                     ->method('writeQuery');
        $this->mainDbMock->expects($this->once())
                     ->method('readQuery')
                     ->willReturn(array(CacheFixture::DRAW_GOOD_ARRAY));
        $this->cacheDrawMock->expects($this->once())
                     ->method('store')
                     ->with(CacheFixture::DRAW_GOOD_ARRAY);
        $this->assertEquals(CacheFixture::DRAW_GOOD_ARRAY, $this->gameHandler->readLastDraw());
    }

    public function testReadLastDrawNoCacheNoDb() : void{
        $this->cacheDrawMock->expects($this->once())
                        ->method('read')
                        ->willReturn(null);
        $this->mainDbMock->expects($this->once())
                     ->method('writeQuery');
        $this->mainDbMock->expects($this->once())
                     ->method('readQuery')
                     ->willReturn(Array());
        $this->assertNull($this->gameHandler->readLastDraw());
    }

    public function testReadLastScoreGoodCache() : void{
        $this->cacheScoreMock->expects($this->once())
                  ->method('read')
                  ->willReturn(CacheFixture::SCORE_GOOD_ARRAY);

        $this->assertEquals(CacheFixture::SCORE_GOOD_ARRAY, $this->gameHandler->readLastScore());
    }

    public function testReadLastScoreNoCacheGoodDb() : void{
        $this->cacheScoreMock->expects($this->once())
                        ->method('read')
                        ->willReturn(null);
        $this->mainDbMock->expects($this->once())
                     ->method('writeQuery');
        $this->mainDbMock->expects($this->once())
                     ->method('readQuery')
                     ->willReturn(array(CacheFixture::SCORE_GOOD_ARRAY));
        $this->cacheScoreMock->expects($this->once())
                     ->method('store')
                     ->with(CacheFixture::SCORE_GOOD_ARRAY);
        $this->assertEquals(CacheFixture::SCORE_GOOD_ARRAY, $this->gameHandler->readLastScore());
    }

    public function testReadLastScoreNoCacheNoDb() : void{
        $this->cacheScoreMock->expects($this->once())
                        ->method('read')
                        ->willReturn(null);
        $this->mainDbMock->expects($this->once())
                     ->method('writeQuery');
        $this->mainDbMock->expects($this->once())
                     ->method('readQuery')
                     ->willReturn(Array());
        $this->assertNull($this->gameHandler->readLastScore());
    }

    public function testWriteOneDrawGoodDb() : void{
        $this->cacheDrawMock->expects($this->once())
                        ->method('clear');
        $this->mainDbMock->expects($this->any())
                     ->method('writeQuery')
                     ->willReturn(1);
        $this->assertTrue($this->gameHandler->writeOneDraw(
            array(GeneratorFixture::FAKE_FIVEWORD, self::$faker->unixTime())));
    }

    public function testWriteOneDrawNoDb() : void{
        $this->cacheDrawMock->expects($this->once())
                        ->method('clear');
        $this->mainDbMock->expects($this->any())
                     ->method('writeQuery')
                     ->willReturn(0);
        $this->assertFalse($this->gameHandler->writeOneDraw(
            array(GeneratorFixture::FAKE_FIVEWORD)));
    }

    public function testFormatDrawGoodArray() : void{
        PHPMockery::mock(__NAMESPACE__, "time")
                    ->andReturn(HandlerFixture::GOOD_TIME_BEFORE);
        $this->assertEquals(HandlerFixture::DRAW_GOOD_JSON,
          $this->gameHandler->formatDraw(HandlerFixture::DRAW_GOOD_ARRAY));
    }

    public function testFormatDrawWrongArray() : void{
        $this->expectException(Exception::class);
        PHPMockery::mock(__NAMESPACE__, "time")
                    ->andReturn(HandlerFixture::GOOD_TIME_BEFORE);
        $this->gameHandler->formatDraw(HandlerFixture::DRAW_WRONG_ARRAY);
    }

    public function testFormatScoreGoodArray() : void{
        $this->assertEquals(HandlerFixture::SCORE_GOOD_JSON,
          $this->gameHandler->formatScore(HandlerFixture::SCORE_GOOD_ARRAY));
    }

    public function testFormatScoreWrongArray() : void{
        $this->expectException(Exception::class);
        $this->gameHandler->formatScore(HandlerFixture::SCORE_WRONG_ARRAY);
    }

}
