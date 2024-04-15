<?php declare(strict_types=1);

namespace Azertype\Cache;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(FileCache::class)]
final class FileCacheTest extends TestCase
{
    private static string $fileName;
    private static string $filePath;
    private array $testArray;
    private string $testJson;
    private FileCache $cache;

    public static function setUpBeforeClass(): void
    {
        self::$fileName = 'foo';
        self::$filePath = $_ENV['ROOT'].$_ENV['CACHE_FILE_DIRNAME'].self::$fileName.'.json';
    }

    public static function tearDownAfterClass(): void
    {
        rmdir($_ENV['ROOT'].$_ENV['CACHE_FILE_DIRNAME']);
    }

    public function setUp():void
    {
        $this->testArray = array('game_id' => 2, 'words' => 'aaa,bbb');
        $this->testJson = '{"game_id":2,"words":"aaa,bbb"}';
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
        file_put_contents(self::$filePath, "not_some_json");
        $outputArray = $this->cache->read();

        $this->assertNull($outputArray);
    }

    public function testReadArray(): void{
        file_put_contents(self::$filePath, $this->testJson);
        $outputArray = $this->cache->read();

        $this->assertSame($this->testArray,$outputArray);
    }

    public function testStoreNull():void{
        $this->cache->store(null);
 
        $this->assertFileDoesNotExist(self::$filePath);
    }

    public function testStoreArrayOnNewFile():void{
        $this->cache->store($this->testArray);
        
        $this->assertFileExists(self::$filePath);
        $this->assertJsonStringEqualsJsonFile(self::$filePath, $this->testJson);
    }

    public function testStoreArrayOnExistingFile():void{
        file_put_contents(self::$filePath, "previous_string");
        $this->cache->store($this->testArray);
 
        $this->assertJsonStringEqualsJsonFile(self::$filePath, $this->testJson);
    }

    public function testClearNonExistingFile():void{
        $this->cache->clear();
 
        $this->assertFileDoesNotExist(self::$filePath);
    }

    public function testClearExistingFile():void{
        file_put_contents(self::$filePath, $this->testJson);
        $this->cache->clear();
 
        $this->assertFileDoesNotExist(self::$filePath);
    }

}
