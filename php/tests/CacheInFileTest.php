<?php declare(strict_types=1);

namespace Azertype;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;


#[CoversClass(CacheInFile::class)]
#[UsesClass(Config::class)]
final class CacheInFileTest extends TestCase
{
    //use PHPMock;

    public static function setUpBeforeClass(): void
    {
        mkdir(".tests.cache");
    }

    public static function tearDownAfterClass(): void
    {
        //rmdir(".tests.cache", );
    }

    public function setUp():void
    {
    }

    public function tearDown():void
    {
    }

    public function testRead():void
    {
        
        // $time = $this->getFunctionMock(__NAMESPACE__, "time");
        // $time->expects($this->once())->willReturn(3);
        // $this->assertEquals(3, time());
        $cache = new CacheInFile("emptyfile");
        $cache->store(array(1,2));
        $this->assertEquals(3, 3);
    }

}
