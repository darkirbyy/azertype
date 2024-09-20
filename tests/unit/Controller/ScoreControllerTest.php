<?php declare(strict_types=1);

namespace Azertype\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use phpmock\mockery\PHPMockery;
use Azertype\Helper\GameHandler;
use Tests\fixture\HandlerFixture;
use Exception;
use Faker\Factory;

#[CoversClass(ScoreController::class)]
final class ScoreControllerTest extends TestCase
{
    private $gameHandlerMock;
    private $scoreController;
    //private static $faker;

    public static function setUpBeforeClass(): void
    {
        //self::$faker = Factory::create('fr_FR');
    }

    public static function tearDownAfterClass(): void
    {

    }

    public function setUp():void
    {
        $this->gameHandlerMock = $this->createMock(GameHandler::class);
        $this->scoreController = new ScoreController($this->gameHandlerMock);
    }

    public function tearDown():void
    {
        \Mockery::close();
    }

    public function testGetScoreValidTime() : void{
        $this->gameHandlerMock->expects($this->once())
                              ->method('readLastScore')
                              ->willReturn(HandlerFixture::SCORE_GOOD_ARRAY);

        $this->gameHandlerMock->expects($this->once())
                              ->method('formatScore')
                              ->with(HandlerFixture::SCORE_GOOD_ARRAY)
                              ->willReturn(HandlerFixture::SCORE_GOOD_JSON);

        PHPMockery::mock(__NAMESPACE__, "time")
                              ->andReturn(HandlerFixture::GOOD_TIME_BEFORE);

        $this->assertEquals(HandlerFixture::SCORE_GOOD_JSON, $this->scoreController->getScore());
    }

    public function testGetDrawInvalidTime() : void{
        $this->gameHandlerMock->expects($this->once())
                              ->method('readLastScore')
                              ->willReturn(HandlerFixture::SCORE_GOOD_ARRAY);


        $this->gameHandlerMock->expects($this->once())
                              ->method('formatScore')
                              ->with(HandlerFixture::SCORE_EXPIRE_ARRAY)
                              ->willReturn(HandlerFixture::SCORE_EXPIRE_JSON);

        PHPMockery::mock(__NAMESPACE__, "time")
                              ->andReturn(HandlerFixture::GOOD_TIME_AFTER);

        $this->assertStringStartsWith(HandlerFixture::SCORE_EXPIRE_JSON, $this->scoreController->getScore());
    }

   }
