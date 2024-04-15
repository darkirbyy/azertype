<?php declare(strict_types=1);

namespace Azertype\Generator;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use phpmock\mockery\PHPMockery;
use Exception;

#[CoversClass(HeroGenerator::class)]
final class HeroGeneratorTest extends TestCase
{

    public function setUp():void
    {
      
    }

    public function tearDown():void
    {
        \Mockery::close();
    }

    public function testGenerateOneWord(): void{
        PHPMockery::mock(__NAMESPACE__, "curl_exec")->andReturn('["éléphantiasiques"]');
        PHPMockery::mock(__NAMESPACE__, "curl_getinfo")->andReturn(200);

        $generator = new HeroGenerator();
        $words = $generator->generate(1);
        $this->assertStringNotContainsString('"', $words);
        $this->assertStringNotContainsString(']', $words);
        $this->assertStringNotContainsString('[', $words);
        $this->assertEquals(1, sizeof(explode(',',$words)));
    }

    public function testGenerateFiveWords(): void{
        PHPMockery::mock(__NAMESPACE__, "curl_exec")
            ->andReturn('["hennissant","déboulonné","légitimité","accoutrasse","coffrerez"]');
        PHPMockery::mock(__NAMESPACE__, "curl_getinfo")->andReturn(215);

        $generator = new HeroGenerator();
        $words = $generator->generate(5);
        $this->assertStringNotContainsString('"', $words);
        $this->assertStringNotContainsString(']', $words);
        $this->assertStringNotContainsString('[', $words);
        $this->assertEquals(5, sizeof(explode(',',$words)));
    }

    public function testGenerateNoWord(): void{
        PHPMockery::mock(__NAMESPACE__, "curl_exec")->andReturn('["éléphantiasiques"]');
        PHPMockery::mock(__NAMESPACE__, "curl_getinfo")->andReturn(200);

        $generator = new HeroGenerator();
        $words = $generator->generate(-1);
        $this->assertEquals("", $words);
    }

    public function testGenerateBadUrl(): void{
        PHPMockery::mock(__NAMESPACE__, "curl_exec")->andReturn(false);
        PHPMockery::mock(__NAMESPACE__, "curl_getinfo")->andReturn(200);
        
        $this->expectException(Exception::class);
        $generator = new HeroGenerator();
        $words = $generator->generate(5);
    }

    public function testGenerateBadHttpCode(): void{
        PHPMockery::mock(__NAMESPACE__, "curl_exec")->andReturn('["éléphantiasiques"]');
        PHPMockery::mock(__NAMESPACE__, "curl_getinfo")->andReturn(404);

        $this->expectException(Exception::class);
        $generator = new HeroGenerator();
        $words = $generator->generate(5);
    }

    public function testGenerateBadBody(): void{
        PHPMockery::mock(__NAMESPACE__, "curl_exec")->andReturn('<!DOCTYPE html><body>test</body>');
        PHPMockery::mock(__NAMESPACE__, "curl_getinfo")->andReturn(200);

        $this->expectException(Exception::class);
        $generator = new HeroGenerator();
        $words = $generator->generate(5);
    }
}
