<?php declare(strict_types=1);

namespace Azertype\Generator;

use Azertype\Helper\DbHandler;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Tests\fixture\GeneratorFixture;

#[CoversClass(SelfGenerator::class)]
final class SelfGeneratorTest extends TestCase
{
    private $generator;
    private $wordsDbMock;
    private string $query;

    public function setUp():void
    {
        $this->wordsDbMock = $this->createMock(DbHandler::class);
        $this->generator = new SelfGenerator();
        $this->generator->initialize($this->wordsDbMock, true);
        $this->query = "SELECT word FROM french ORDER BY RANDOM() LIMIT :count";

    }

    public function testGenerateOneWord(): void{
        $this->wordsDbMock->expects($this->once())
                          ->method('readQuery')
                          ->with($this->query, [1])
                          ->willReturn(GeneratorFixture::SELF_ONEWORD);
        $words = $this->generator->generate(1);
        $this->assertStringEndsNotWith(',', $words);
    }

    public function testGenerateFiveWords(): void{
        $this->wordsDbMock->expects($this->once())
                          ->method('readQuery')
                          ->with($this->query, [5])
                          ->willReturn(GeneratorFixture::SELF_FIVEWORDS);
        $words = $this->generator->generate(5);
        $this->assertStringEndsNotWith(',', $words);

        $words_array = explode(',',$words);
        $this->assertEquals(5, sizeof($words_array));

        $words_length = array_map('mb_strlen', $words_array);
        $words_length_sorted = $words_length;
        sort($words_length_sorted);
        $this->assertEquals($words_length, $words_length_sorted);
    }

    public function testGenerateNoWord(): void{
        $words = $this->generator->generate(-1);
        $this->assertEquals("", $words);
    }

}
