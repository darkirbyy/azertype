<?php declare(strict_types=1);

namespace Azertype\Cache;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use Azertype\Config;

#[CoversClass(CacheInFile::class)]
#[UsesClass(Config::class)]
final class CacheInFileTest extends TestCase
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
        rmdir(Config::ROOT.config::FILECACHE_DIRNAME);
        rmdir(Config::ROOT);
    }

    public function setUp():void
    {
        $this->fileName = 'foo.txt';
        $this->filePath = Config::ROOT.Config::FILECACHE_DIRNAME.$this->fileName;

        $this->testArray = array('game_id' => 2, 'words' => 'aaa,bbb');
        $this->testJson = '{"game_id":2,"words":"aaa,bbb"}';
    }

    public function tearDown():void
    {
        if(file_exists($this->filePath))
            unlink($this->filePath);
    }

    public function testReadNonExistingFile(): void{
        $cache = new CacheInFile($this->fileName);
        $outputArray = $cache->read();

        $this->assertSame(null,$outputArray);
    }

    public function testReadFail(): void{
        $cache = new CacheInFile($this->fileName);
        file_put_contents($this->filePath, "not_some_json");
        $outputArray = $cache->read();

        $this->assertSame(null,$outputArray);
    }

    public function testReadArray(): void{
        $cache = new CacheInFile($this->fileName);
        file_put_contents($this->filePath, $this->testJson);
        $outputArray = $cache->read();

        $this->assertSame($this->testArray,$outputArray);
    }

    public function testStoreNull():void{
        $cache = new CacheInFile($this->fileName);
        $cache->store(null);
 
        $this->assertSame(false,file_exists($this->filePath));
    }

    public function testStoreArrayOnNewFile():void{
        $cache = new CacheInFile($this->fileName);
        $cache->store($this->testArray);
        
         $this->assertSame(true,file_exists($this->filePath));
         $this->assertSame($this->testJson, file_get_contents($this->filePath));
    }

    public function testStoreArrayOnExistingFile():void{
        $cache = new CacheInFile($this->fileName);
        file_put_contents($this->filePath, "previous_string");
        $cache->store(array(1));
 
        $this->assertSame('[1]', file_get_contents($this->filePath));
    }

    public function testClearNonExistingFile():void{
        $cache = new CacheInFile($this->fileName);
        $cache->clear();
 
        $this->assertSame(false, file_exists($this->filePath));
    }

    public function testClearExistingFile():void{
        $cache = new CacheInFile($this->fileName);
        file_put_contents($this->filePath, $this->testJson);
        $cache->clear();
 
        $this->assertSame(false, file_exists($this->filePath));
    }

}
