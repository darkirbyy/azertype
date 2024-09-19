<?php declare(strict_types=1);

namespace Azertype\Cache;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\fixture\CacheFixture;

#[CoversClass(FileCache::class)]
final class FileCacheTest extends TestCase
{
    private static string $fileName;
    private static string $filePath;
    private FileCache $cache;

    public static function setUpBeforeClass(): void
    {
        self::$fileName = CacheFixture::FILENAME;
        self::$filePath = $_ENV['REL_ROOT'].$_ENV['CACHEFILE_DIR'].self::$fileName.'.json';
    }

    public static function tearDownAfterClass(): void
    {
        rmdir($_ENV['REL_ROOT'].$_ENV['CACHEFILE_DIR']);
    }

    public function setUp():void
    {
        $this->cache = new FileCache(self::$fileName);
    }

    public function tearDown():void
    {
        if(file_exists(self::$filePath))
            unlink(self::$filePath);
    }

    public function testReadNonExistingFile(): void{
        $outputArray = $this->cache->read();

        $this->assertNull($outputArray);
    }

    public function testReadFail(): void{
        file_put_contents(self::$filePath, CacheFixture::DRAW_WRONG_JSON);
        $outputArray = $this->cache->read();

        $this->assertNull($outputArray);
    }

    public function testReadArray(): void{
        file_put_contents(self::$filePath, CacheFixture::DRAW_GOOD_JSON);
        $outputArray = $this->cache->read();

        $this->assertSame(CacheFixture::DRAW_GOOD_ARRAY,$outputArray);
    }

    public function testStoreNull():void{
        $this->cache->store(null);
 
        $this->assertFileDoesNotExist(self::$filePath);
    }

    public function testStoreArrayOnNewFile():void{
        $this->cache->store(CacheFixture::DRAW_GOOD_ARRAY);
        
        $this->assertFileExists(self::$filePath);
        $this->assertJsonStringEqualsJsonFile(self::$filePath, CacheFixture::DRAW_GOOD_JSON);
    }

    public function testStoreArrayOnExistingFile():void{
        file_put_contents(self::$filePath, CacheFixture::DRAW_OTHER_JSON);
        $this->cache->store(CacheFixture::DRAW_GOOD_ARRAY);
 
        $this->assertJsonStringEqualsJsonFile(self::$filePath, CacheFixture::DRAW_GOOD_JSON);
    }

    public function testClearNonExistingFile():void{
        $this->cache->clear();
 
        $this->assertFileDoesNotExist(self::$filePath);
    }

    public function testClearExistingFile():void{
        file_put_contents(self::$filePath, CacheFixture::DRAW_GOOD_JSON);
        $this->cache->clear();
 
        $this->assertFileDoesNotExist(self::$filePath);
    }

}
