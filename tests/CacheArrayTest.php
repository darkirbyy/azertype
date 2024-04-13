<?php declare(strict_types=1);

namespace Azertype;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

class Config{
    const ROOT = __DIR__.DIRECTORY_SEPARATOR.
                "..".DIRECTORY_SEPARATOR.
                ".tests.cache".DIRECTORY_SEPARATOR;
    const CACHE_DIRNAME = ".db.cache".DIRECTORY_SEPARATOR;
}
#[CoversClass(CacheArray::class)]
#[UsesClass(Config::class)]
final class CacheArrayTest extends TestCase
{
    private string $fileName;
    private string $filePath;
    private array $testArray;
    private string $testJson;

    public static function setUpBeforeClass(): void
    {
        mkdir(Config::ROOT);
    }

    public static function tearDownAfterClass(): void
    {
        rmdir(Config::ROOT.config::CACHE_DIRNAME);
        rmdir(Config::ROOT);
    }


    public function setUp():void
    {
        $this->fileName = 'foo.txt';
        $this->filePath = Config::ROOT.Config::CACHE_DIRNAME.$this->fileName;

        $this->testArray = array('game_id' => 2, 'words' => 'aaa,bbb');
        $this->testJson = '{"game_id":2,"words":"aaa,bbb"}';
    }

    public function tearDown():void
    {
        if(file_exists($this->filePath))
            unlink($this->filePath);
    }

    public function testReadNonExistingFile(): void{
        $cache = new CacheArray($this->fileName);
        $outputArray = $cache->read();

        $this->assertSame(null,$outputArray);
    }

    public function testReadFail(): void{
        file_put_contents($this->filePath, "not_some_json");
        $cache = new CacheArray($this->fileName);
        $outputArray = $cache->read();

        $this->assertSame(null,$outputArray);
    }

    public function testReadArray(): void{
        file_put_contents($this->filePath, $this->testJson);
        $cache = new CacheArray($this->fileName);
        $outputArray = $cache->read();

        $this->assertSame($this->testArray,$outputArray);
    }

    public function testStoreNull():void{
        $cache = new CacheArray($this->fileName);
        $cache->store(null);
 
        $this->assertSame(false,file_exists($this->filePath));
    }

    public function testStoreArrayOnNewFile():void{
        $cache = new CacheArray($this->fileName);
        $cache->store($this->testArray);
        
         $this->assertSame(true,file_exists($this->filePath));
         $this->assertSame($this->testJson, file_get_contents($this->filePath));
    }

    public function testStoreArrayOnExistingFile():void{
        file_put_contents($this->filePath, "previous_string");
        $cache = new CacheArray($this->fileName);
        $cache->store(array(1));
 
        $this->assertSame('[1]', file_get_contents($this->filePath));
    }

}
