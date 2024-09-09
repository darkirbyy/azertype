<?php declare(strict_types=1);

namespace Azertype\Handler;

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

#[CoversClass(DrawHandler::class)]
final class DrawHandlerTest extends TestCase
{
    private $cacheMock;
    private $mainDbMock;
    private $drawHandler;
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
        $this->cacheMock = $this->createMock(AbstractCache::class);
        $this->mainDbMock = $this->createMock(DbHandler::class);
        $this->drawHandler = new DrawHandler($this->mainDbMock, $this->cacheMock);
    }

    public function tearDown():void
    {
        \Mockery::close();
    }

    public function testReadLastDrawGoodCache() : void{
        $this->cacheMock->expects($this->once())
                  ->method('read')
                  ->willReturn(CacheFixture::GOOD_ARRAY);

        $this->assertEquals(CacheFixture::GOOD_ARRAY, $this->drawHandler->readLastDraw());
    }

    public function testReadLastDrawNoCacheGoodDb() : void{
        $this->cacheMock->expects($this->once())
                        ->method('read')
                        ->willReturn(null);
        $this->mainDbMock->expects($this->once())
                     ->method('writeQuery');
        $this->mainDbMock->expects($this->once())
                     ->method('readQuery')
                     ->willReturn(array(CacheFixture::GOOD_ARRAY));
        $this->cacheMock->expects($this->once())
                     ->method('store')
                     ->with(CacheFixture::GOOD_ARRAY);
        $this->assertEquals(CacheFixture::GOOD_ARRAY, $this->drawHandler->readLastDraw());
    }

    public function testReadLastDrawNoCacheNoDb() : void{
        $this->cacheMock->expects($this->once())
                        ->method('read')
                        ->willReturn(null);
        $this->mainDbMock->expects($this->once())
                     ->method('writeQuery');
        $this->mainDbMock->expects($this->once())
                     ->method('readQuery')
                     ->willReturn(Array());
        $this->assertNull($this->drawHandler->readLastDraw());
    }

    public function testWriteOneDrawGoodDb() : void{
        $this->cacheMock->expects($this->once())
                        ->method('clear');
        $this->mainDbMock->expects($this->any())
                     ->method('writeQuery')
                     ->willReturn(1);
        $this->assertTrue($this->drawHandler->writeOneDraw(
            array(GeneratorFixture::FAKE_FIVEWORD, self::$faker->unixTime())));
    }

    public function testWriteOneDrawNoDb() : void{
        $this->cacheMock->expects($this->once())
                        ->method('clear');
        $this->mainDbMock->expects($this->any())
                     ->method('writeQuery')
                     ->willReturn(0);
        $this->assertFalse($this->drawHandler->writeOneDraw(
            array(GeneratorFixture::FAKE_FIVEWORD)));
    }

    public function testFormatDrawGoodArray() : void{
        PHPMockery::mock(__NAMESPACE__, "time")
                    ->andReturn(HandlerFixture::GOOD_TIME_BEFORE);
        $this->assertEquals(HandlerFixture::GOOD_JSON,
          $this->drawHandler->formatDraw(HandlerFixture::GOOD_ARRAY));
    }

    public function testFormatDrawWrongArray() : void{
        $this->expectException(Exception::class);
        PHPMockery::mock(__NAMESPACE__, "time")
                    ->andReturn(HandlerFixture::GOOD_TIME_BEFORE);
        $this->drawHandler->formatDraw(HandlerFixture::WRONG_ARRAY);
    }

}
