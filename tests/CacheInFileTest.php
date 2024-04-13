<?php declare(strict_types=1);

namespace Azertype;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;


#[CoversClass(CacheInFile::class)]
#[UsesClass(Config::class)]
final class CacheInFileTest extends TestCase
{

    public static function setUpBeforeClass(): void
    {
        //mkdir($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."cache");
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

    public function testStore():void
    {
        $configMock = $this->createMock(Config::class);
        $cache = new CacheInFile("emptyfile");

        $configMock->expects($this->once())
                    ->method('getRootPath')
                    ->willReturn('.tests.cache'.DIRECTORY_SEPARATOR);

        $cache->store(array(1,2));
        $this->assertEquals(true,
        file_exists($_SERVER['DOCUMENT_ROOT'].'.tests.cache/.db.cache/emptyFile'));
    }

}
