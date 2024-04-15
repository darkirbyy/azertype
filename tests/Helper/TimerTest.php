<?php declare(strict_types=1);

namespace Azertype\Helper;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Timer::class)]
final class TimerTest extends TestCase
{

    public function testCurrentTimeStamp(): void{
        $this->assertIsInt(Timer::currentTimestamp());
        $this->assertGreaterThan(0, Timer::currentTimestamp());
    }
    public function testFloorInterval(): void{
        $this->assertEquals(1798653300, Timer::floorInterval(1798653585, 5));
        $this->assertEquals(1712386800, Timer::floorInterval(1712387866, 60));
        $this->assertEquals(1800226800, Timer::floorInterval(1800281637, 1440));
        $this->assertEquals(1712181600, Timer::floorInterval(1712267945, 1440));
        $this->assertNull(Timer::floorInterval(-175121, 60));
        $this->assertNull(Timer::floorInterval(1798653585, 2880));
    }

    public function testCeilInterval(): void{
        $this->assertEquals(1798653600, Timer::ceilInterval(1798653585, 5));
        $this->assertEquals(1800313200, Timer::ceilInterval(1800281637, 1440));
        $this->assertNull(Timer::ceilInterval(-175121, 60));
        $this->assertNull(Timer::ceilInterval(1798653585, 2880));
    }

    public function testAreInSameInterval(): void{
        $this->assertFalse(Timer::areInSameInterval(1760554260, 1760554177, 5));
        $this->assertTrue(Timer::areInSameInterval(1582273079, 1582275423, 60));
        $this->assertFalse(Timer::areInSameInterval(1553479864, 1553465464,1440));
        $this->assertFalse(Timer::areInSameInterval(1716328865, 1716328787,1440));
    }

}
