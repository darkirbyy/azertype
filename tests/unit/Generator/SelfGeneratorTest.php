<?php declare(strict_types=1);

namespace Azertype\Generator;

use Azertype\Helper\DbHandler;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(SelfGenerator::class)]
final class SelfGeneratorTest extends TestCase
{
    private SelfGenerator $generator;
    private DbHandler $wordsDbMock;
    private string $query;

    public function setUp():void
    {
        $this->wordsDbMock = $this->createMock(DbHandler::class);
        $this->generator = new SelfGenerator();
        $this->generator->initialize($this->wordsDbMock, true);
        $this->query = "SELECT word FROM french WHERE length BETWEEN :min AND :max ORDER BY RANDOM() LIMIT :count";
    }

    public function testGenerateOneWord(): void{
        $this->wordsDbMock->expects($this->once())
                          ->method('readQuery')
                          ->with($this->query, [1,2,1])
                          ->willReturn([['word' => 'le']]);
        $words = $this->generator->generate(1);
        $this->assertStringEndsNotWith(',', $words);
        $this->assertTrue(mb_strlen($words) >= 1 && mb_strlen($words) <= 2);
    }

    // public function testGenerateFiveWords(): void{
    //     $words = $this->generator->generate(5);
    //     $this->assertStringEndsNotWith(',', $words);
    //     $words_array = explode(',',$words);
    //     $this->assertEquals(5, sizeof($words_array));
    //     $this->assertTrue(mb_strlen($words_array[4]) >= 9 && mb_strlen($words_array[4]) <= 10);
    // }

    public function testGenerateNoWord(): void{
        $words = $this->generator->generate(-1);
        $this->assertEquals("", $words);
    }

}
