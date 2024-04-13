<?php declare(strict_types=1);

namespace Azertype;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;


#[CoversClass(CacheArray::class)]
#[UsesClass(Config::class)]
final class CacheArrayTest extends TestCase
{
    private string $fileName;
    private string $filePath;

    public function setUp():void
    {
        $this->fileName = 'foo.txt';
        $this->filePath = Config::ROOT.Config::CACHE_DIRNAME.$this->fileName;
    }

    public function tearDown():void
    {
        if(file_exists($this->filePath))
            unlink($this->filePath);
    }

    public function testStoreNormalArray():void
    {
        $testArray = array('game_id' => 2, 'words' => 'aaa,bbb');

        $cache = new CacheArray($this->fileName);
        $cache->store($testArray);
        
         $this->assertSame(true,file_exists($this->filePath));
         $this->assertSame('{"game_id":2,"words":"aaa,bbb"}', file_get_contents($this->filePath));
    }

    public function testStoreNull():void
    {
        $cache = new CacheArray($this->fileName);
        $cache->store(null);
 
        $this->assertSame(false,file_exists($this->filePath));
    }

    public function testStoreOnExistingFile():void
    {
        file_put_contents($this->filePath, "foo");
        $cache = new CacheArray($this->fileName);
        $cache->store(array(1));
 
        $this->assertSame('[1]', file_get_contents($this->filePath));
    }

}
