<?php declare(strict_types=1);

namespace Azertype\Generator;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use phpmock\mockery\PHPMockery;
use Azertype\Config;
use Exception;

#[CoversClass(HeroGenerator::class)]
#[UsesClass(Config::class)]
final class HeroGeneratorTest extends TestCase
{
    public function testGenerateOneWord(): void{
        PHPMockery::mock(__NAMESPACE__, "curl_exec")
            ->andReturn('["produise"]');

        $generator = new HeroGenerator(Config::HERO_FAKE_URL);
        $words = $generator->generate(1);
        $this->assertStringNotContainsString('"', $words);
        $this->assertStringNotContainsString(']', $words);
        $this->assertStringNotContainsString('[', $words);
        $this->assertEquals(1, sizeof(explode(',',$words)));

        \Mockery::close();
    }

    public function testGenerateFiveWords(): void{
        PHPMockery::mock(__NAMESPACE__, "curl_exec")
            ->andReturn('["hennissant","déboulonné","légitimité","accoutrasse","coffrerez"]');

        $generator = new HeroGenerator(Config::HERO_FAKE_URL);
        $words = $generator->generate(5);
        $this->assertStringNotContainsString('"', $words);
        $this->assertStringNotContainsString(']', $words);
        $this->assertStringNotContainsString('[', $words);
        $this->assertEquals(5, sizeof(explode(',',$words)));

        \Mockery::close();
    }

    public function testGenerateNoWord(): void{
        $generator = new HeroGenerator(Config::HERO_FAKE_URL);
        $words = $generator->generate(-1);
        $this->assertEquals("", $words);
    }

    public function testGenerateBadUrl(): void{
        $this->expectException(Exception::class);
        $generator = new HeroGenerator("wrong_url");
        $words = $generator->generate(5);
    }
}
