<?php declare(strict_types=1);

namespace Azertype\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use phpmock\mockery\PHPMockery;
use Azertype\Cache\AbstractCache;
use Azertype\Generator\AbstractGenerator;
use Azertype\Handler\DrawHandler;
use Tests\fixture\CacheFixture;
use Tests\fixture\HandlerFixture;
use Tests\fixture\GeneratorFixture;
use Azertype\Helper\Timer;
use Exception;
use Faker\Factory;

#[CoversClass(DrawController::class)]
final class DrawControllerTest extends TestCase
{
    private $drawHandlerMock;
    private $timerMock;
    private $generatorMock;
    private $drawController;
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
        $this->drawHandlerMock = $this->createMock(DrawHandler::class);
        $this->timerMock = $this->createMock(Timer::class);
        $this->generatorMock = $this->createMock(AbstractGenerator::class);
        $this->drawController = new DrawController($this->drawHandlerMock, $this->timerMock, $this->generatorMock);
    }

    public function tearDown():void
    {
        \Mockery::close();
    }

    public function testGetDrawValidTime() : void{
        $this->drawHandlerMock->expects($this->once())
                              ->method('readLastDraw')
                              ->willReturn(HandlerFixture::DRAW_GOOD_ARRAY);

        $this->drawHandlerMock->expects($this->once())
                              ->method('formatDraw')
                              ->with(HandlerFixture::DRAW_GOOD_ARRAY)
                              ->willReturn(HandlerFixture::DRAW_GOOD_JSON);

        PHPMockery::mock(__NAMESPACE__, "time")
                              ->andReturn(HandlerFixture::GOOD_TIME_BEFORE);

        $this->assertEquals(HandlerFixture::DRAW_GOOD_JSON, $this->drawController->getDraw());
    }

    public function testGetDrawInvalidTime() : void{
        $this->drawHandlerMock->expects($this->exactly(2))
                              ->method('readLastDraw')
                              ->willReturn(HandlerFixture::DRAW_GOOD_ARRAY);

        $this->drawHandlerMock->expects($this->once())
                              ->method('formatDraw')
                              ->with(HandlerFixture::DRAW_GOOD_ARRAY)
                              ->willReturn(HandlerFixture::DRAW_GOOD_JSON);

        $this->generatorMock->expects($this->once())
                            ->method('generate')
                            ->with($_ENV['WORDS_PER_DRAW'])
                            ->willReturn(GeneratorFixture::FAKE_FIVEWORD);
        
        $this->timerMock->expects($this->once())
                        ->method('ceilTimestamp')
                        ->with(HandlerFixture::GOOD_TIME_AFTER)
                        ->willReturn(HandlerFixture::GOOD_TIME_AFTER+5);

        $this->drawHandlerMock->expects($this->once())
                        ->method('writeOneDraw')
                        ->with(array(HandlerFixture::GOOD_TIME_AFTER+5, GeneratorFixture::FAKE_FIVEWORD));

        PHPMockery::mock(__NAMESPACE__, "time")
                              ->andReturn(HandlerFixture::GOOD_TIME_AFTER);

        $this->assertEquals(HandlerFixture::DRAW_GOOD_JSON, $this->drawController->getDraw());
    }

   }
