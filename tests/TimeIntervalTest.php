<?php declare(strict_types=1);

namespace Azertype;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(TimeInterval::class)]
#[UsesClass(Config::class)]
final class TimeIntervalTest extends TestCase
{
    public function testGetNearestInterval(): void{
        $this->assertSame(1798653300, TimeInterval::getNearestInterval(1798653585, 5));
        $this->assertSame(1712386800, TimeInterval::getNearestInterval(1712387866, 60));
        $this->assertSame(1800226800, TimeInterval::getNearestInterval(1800281637, 1440));
        $this->assertSame(1712181600, TimeInterval::getNearestInterval(1712267945, 1440));
        $this->assertSame(null, TimeInterval::getNearestInterval(-175121, 60));
        $this->assertSame(null, TimeInterval::getNearestInterval(1798653585, 2880));
    }

    public function testAreInSameInterval(): void{
        $this->assertSame(false, TimeInterval::areInSameInterval(1760554260, 1760554177, 5));
        $this->assertSame(true, TimeInterval::areInSameInterval(1582273079, 1582275423, 60));
        $this->assertSame(false, TimeInterval::areInSameInterval(1553479864, 1553465464,1440));
        $this->assertSame(false, TimeInterval::areInSameInterval(1716328865, 1716328787,1440));
    }

}
