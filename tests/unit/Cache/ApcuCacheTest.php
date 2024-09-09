<?php declare(strict_types=1);

namespace Azertype\Cache;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\fixture\CacheFixture;

#[CoversClass(ApcuCache::class)]
final class ApcuCacheTest extends TestCase
{
    private static string $key;
    private ApcuCache $cache;

    public static function setUpBeforeClass(): void
    {
        self::$key = "UnitTestAcpu";
    }

    public function setUp():void
    {
        if (!function_exists('apcu_clear_cache') || !apcu_enabled()) {
            $this->markTestSkipped('APCu is not available.');
        }
        apcu_clear_cache();
        $this->cache = new ApcuCache(self::$key);
    }

    public function testReadNonExistingVariable(): void{
        $outputArray = $this->cache->read();

        $this->assertNull($outputArray);
    }

    public function testReadArray(): void{
        apcu_store(self::$key, CacheFixture::GOOD_ARRAY);
        $outputArray = $this->cache->read();

        $this->assertSame(CacheFixture::GOOD_ARRAY,$outputArray);
    }

    public function testStoreNull():void{
        $this->cache->store(null);
 
        $this->assertFalse(apcu_exists(self::$key));
    }

    public function testStoreArrayNewVariable():void{
        $this->cache->store(CacheFixture::GOOD_ARRAY);
        
        $this->assertSame(CacheFixture::GOOD_ARRAY, apcu_fetch(self::$key));
    }

    public function testStoreArrayOnExistingVariable():void{
        apcu_store(self::$key, CacheFixture::OTHER_ARRAY);
        $this->cache->store(CacheFixture::GOOD_ARRAY);
 
        $this->assertSame(CacheFixture::GOOD_ARRAY, apcu_fetch(self::$key));
    }

    public function testClearNonExistingVariable():void{
        $this->cache->clear();
 
        $this->assertFalse(apcu_exists(self::$key));
    }

    public function testClearExistingVariable():void{
        apcu_store(self::$key, CacheFixture::GOOD_ARRAY);
        $this->cache->clear();
 
        $this->assertFalse(apcu_exists(self::$key));
    }

}
