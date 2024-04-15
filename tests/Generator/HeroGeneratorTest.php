<?php declare(strict_types=1);

namespace Azertype\Generator;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(FakeGenerator::class)]
final class FakeGeneratorTest extends TestCase
{
    private FakeGenerator $generator;

    public function testGenerateOneWord(): void{
        $this->generator = new FakeGenerator();
        $words = $this->generator->generate(1);
        $this->assertStringEndsNotWith(',', $words);
        $this->assertEquals(3, strlen($words));
    }
    public function testGenerateTenWords(): void{
        $this->generator = new FakeGenerator();
        $words = $this->generator->generate(10);
        $this->assertStringEndsNotWith(',', $words);
        $this->assertEquals(10, sizeof(explode(',',$words)));
    }

    public function testGenerateNoWord(): void{
        $this->generator = new FakeGenerator();
        $words = $this->generator->generate(-1);
        $this->assertEquals("", $words);
    }

}
