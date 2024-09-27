<?php

declare(strict_types=1);

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

    public static function setUpBeforeClass(): void {}

    public static function tearDownAfterClass(): void {}

    public function setUp(): void
    {
        $this->gameHandlerMock = $this->createMock(GameHandler::class);
        $this->scoreController = new ScoreController($this->gameHandlerMock);
    }

    public function tearDown(): void
    {
        \Mockery::close();
    }

    public function testGetScoreValidTime(): void
    {
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

    public function testGetScoreInvalidTime(): void
    {
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

    public function testPostScoreInvalidBody(): void
    {
        PHPMockery::mock(__NAMESPACE__, "file_get_contents")
            ->andReturn(HandlerFixture::POST_WRONG_JSON);

        $this->expectException(Exception::class);
        $this->scoreController->postScore();
    }


    public function testPostScoreGameExpired(): void
    {
        PHPMockery::mock(__NAMESPACE__, "file_get_contents")
            ->andReturn(HandlerFixture::POST_GOOD_JSON_EXPIRED);

        $this->gameHandlerMock->expects($this->once())
            ->method('readLastScore')
            ->willReturn(HandlerFixture::SCORE_GOOD_ARRAY);

        PHPMockery::mock(__NAMESPACE__, "time")
            ->andReturn(HandlerFixture::GOOD_TIME_BEFORE);

        $this->expectException(Exception::class);
        $this->scoreController->postScore();
    }

    public function testPostScoreNegativeTime(): void
    {
        PHPMockery::mock(__NAMESPACE__, "file_get_contents")
            ->andReturn(HandlerFixture::POST_GOOD_JSON_NEGATIVE);

        $this->gameHandlerMock->expects($this->once())
            ->method('readLastScore')
            ->willReturn(HandlerFixture::SCORE_GOOD_ARRAY);

        PHPMockery::mock(__NAMESPACE__, "time")
            ->andReturn(HandlerFixture::GOOD_TIME_BEFORE);

        $this->gameHandlerMock->expects($this->once())
            ->method('updateLastScore')
            ->with(HandlerFixture::POST_GOOD_ARRAY_NEGATIVE);

        $this->scoreController->postScore();
    }

    public function testPostScorePositiveTime(): void
    {
        PHPMockery::mock(__NAMESPACE__, "file_get_contents")
            ->andReturn(HandlerFixture::POST_GOOD_JSON_POSITIVE);

        $this->gameHandlerMock->expects($this->once())
            ->method('readLastScore')
            ->willReturn(HandlerFixture::SCORE_GOOD_ARRAY);

        PHPMockery::mock(__NAMESPACE__, "time")
            ->andReturn(HandlerFixture::GOOD_TIME_BEFORE);

        $this->gameHandlerMock->expects($this->once())
            ->method('updateLastScore')
            ->with(HandlerFixture::POST_GOOD_ARRAY_POSITIVE);

        $this->scoreController->postScore();
    }
}
