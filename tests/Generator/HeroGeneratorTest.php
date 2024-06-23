<?php declare(strict_types=1);

namespace Azertype\Generator;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use phpmock\mockery\PHPMockery;
use Tests\Fixture\GeneratorFixture;
use Faker\Factory;
use Exception;

#[CoversClass(HeroGenerator::class)]
final class HeroGeneratorTest extends TestCase
{
    private static $faker;

    public static function setUpBeforeClass(): void
    {
        self::$faker = Factory::create('fr_FR');
    }

    public function tearDown():void
    {
        \Mockery::close();
    }

    public function testGenerateOneWord(): void{
        PHPMockery::mock(__NAMESPACE__, "curl_exec")
                    ->andReturn(GeneratorFixture::HERO_ONEWORD);
        PHPMockery::mock(__NAMESPACE__, "curl_getinfo")
                    ->andReturn(self::$faker->numberBetween(200,299));

        $generator = new HeroGenerator();
        $words = $generator->generate(1);
        $this->assertStringNotContainsString('"', $words);
        $this->assertStringNotContainsString(']', $words);
        $this->assertStringNotContainsString('[', $words);
        $this->assertEquals(1, sizeof(explode(',',$words)));
    }

    public function testGenerateFiveWords(): void{
        PHPMockery::mock(__NAMESPACE__, "curl_exec")
                    ->andReturn(GeneratorFixture::HERO_FIVEWORDS);
        PHPMockery::mock(__NAMESPACE__, "curl_getinfo")
                    ->andReturn(self::$faker->numberBetween(200,299));

        $generator = new HeroGenerator();
        $words = $generator->generate(5);
        $this->assertStringNotContainsString('"', $words);
        $this->assertStringNotContainsString(']', $words);
        $this->assertStringNotContainsString('[', $words);
        $this->assertEquals(5, sizeof(explode(',',$words)));
    }

    public function testGenerateNoWord(): void{
        PHPMockery::mock(__NAMESPACE__, "curl_exec")
                    ->andReturn(GeneratorFixture::HERO_ONEWORD);
        PHPMockery::mock(__NAMESPACE__, "curl_getinfo")
                    ->andReturn(self::$faker->numberBetween(200,299));

        $generator = new HeroGenerator();
        $words = $generator->generate(-1);
        $this->assertEquals("", $words);
    }

    public function testGenerateWrongUrl(): void{
        PHPMockery::mock(__NAMESPACE__, "curl_exec")
                    ->andReturn(false);
        PHPMockery::mock(__NAMESPACE__, "curl_getinfo")
                    ->andReturn(self::$faker->numberBetween(200,299));
        
        $this->expectException(Exception::class);
        $generator = new HeroGenerator();
        $words = $generator->generate(5);
    }

    public function testGenerateBadHttpCode(): void{
        PHPMockery::mock(__NAMESPACE__, "curl_exec")
                    ->andReturn(GeneratorFixture::HERO_ONEWORD);
        PHPMockery::mock(__NAMESPACE__, "curl_getinfo")
                    ->andReturn(self::$faker->numberBetween(300,999));

        $this->expectException(Exception::class);
        $generator = new HeroGenerator();
        $words = $generator->generate(5);
    }

    public function testGenerateXSSInjection(): void{
        PHPMockery::mock(__NAMESPACE__, "curl_exec")
                    ->andReturn(GeneratorFixture::HERO_XSSWORDS);
        PHPMockery::mock(__NAMESPACE__, "curl_getinfo")
                    ->andReturn(self::$faker->numberBetween(200,299));
        
        $this->expectException(Exception::class);
        $generator = new HeroGenerator();
        $words = $generator->generate(5);
    }
}
