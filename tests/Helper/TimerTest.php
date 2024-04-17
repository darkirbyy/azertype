<?php declare(strict_types=1);

namespace Azertype\Helper;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Faker\Factory;

#[CoversClass(Timer::class)]
final class TimerTest extends TestCase
{
    private static $faker;
    private string $date;

    public static function setUpBeforeClass(): void
    {
        self::$faker = Factory::create();
    }

    public function setUp() : void {
        date_default_timezone_set(self::$faker->timezone());
        $this->date = self::$faker->date();
    }

    public function testInitAbsurd(): void{
        $timer = new Timer('aa:-50:00', '48:00:00');

        $this->assertEquals(strtotime($this->date.' 04:00:00'),
            $timer->floorTimestamp(strtotime($this->date.' 04:12:13')));
    }

    public function testFloorInterval1(): void{
        $timer = new Timer('12:00:00', '00:05:00');

        $this->assertEquals(strtotime($this->date.' 11:20:00'),
            $timer->floorTimestamp(strtotime($this->date.' 11:23:14')));
        $this->assertEquals(strtotime($this->date.' 18:30:00'),
            $timer->floorTimestamp(strtotime($this->date.' 18:31:59')));
        $this->assertNull($timer->floorTimestamp(-168415));
    }

    public function testFloorInterval2(): void{
        $timer = new Timer('21:30:00', '01:00:00');

        $this->assertEquals(strtotime($this->date.' 23:30:00 -1 day'),
            $timer->floorTimestamp(strtotime($this->date.' 00:22:00')));
        $this->assertEquals(strtotime($this->date.' 22:30:00'),
            $timer->floorTimestamp(strtotime($this->date.' 22:31:59')));   
    }

    public function testFloorInterval3(): void{
        $timer = new Timer('01:00:00', '00:07:20');

        $this->assertEquals(strtotime($this->date.' 05:31:20'),
            $timer->floorTimestamp(strtotime($this->date.' 05:32:17')));
        $this->assertEquals(strtotime($this->date.' 00:57:20'),
             $timer->floorTimestamp(strtotime($this->date.' 00:59:59')));   
    }

    public function testFloorInterval4(): void{
        $timer = new Timer('12:00:00', '24:00:00');

        $this->assertEquals(strtotime($this->date.' 12:00:00'),
            $timer->floorTimestamp(strtotime($this->date.' 19:52:17')));
        $this->assertEquals(strtotime($this->date.' 12:00:00 -1 day'),
             $timer->floorTimestamp(strtotime($this->date.' 10:56:20')));   
    }

    public function testCeilInterval1(): void{
        $timer = new Timer('15:00:00', '00:15:00');

        $this->assertEquals(strtotime($this->date.' 09:15:00'),
            $timer->ceilTimestamp(strtotime($this->date.' 09:08:32')));
        $this->assertEquals(strtotime($this->date.' 00:00:00 +1 day'),
            $timer->ceilTimestamp(strtotime($this->date.' 23:59:07')));
        $this->assertNull($timer->ceilTimestamp(-168415));
    }

    public function testCeilInterval2(): void{
        $timer = new Timer('20:00:10', '02:00:00');

        $this->assertEquals(strtotime($this->date.' 22:00:10'),
            $timer->ceilTimestamp(strtotime($this->date.' 20:30:00')));
        $this->assertEquals(strtotime($this->date.' 20:00:10'),
            $timer->ceilTimestamp(strtotime($this->date.' 18:45:03')));   
    }

    public function testCeilInterval3(): void{
        $timer = new Timer('01:00:00', '00:07:20');

        $this->assertEquals(strtotime($this->date.' 05:38:40'),
            $timer->ceilTimestamp(strtotime($this->date.' 05:32:17')));
        $this->assertEquals(strtotime($this->date.' 01:00:00'),
             $timer->ceilTimestamp(strtotime($this->date.' 00:59:59')));   
    }

    public function testAreInSameInterval1(): void{
        $timer = new Timer('08:15:00', '00:30:00');

        $this->assertFalse($timer->inSameInterval(
            strtotime($this->date.' 08:38:40'),strtotime($this->date.' 08:46:17')));
        $this->assertTrue($timer->inSameInterval(
            strtotime($this->date.' 07:12:02'),strtotime($this->date.' 06:55:00'))); 
    }

    public function testAreInSameInterval2(): void{
        $timer = new Timer('00:00:00', '00:11:00');

        $this->assertFalse($timer->inSameInterval(
            strtotime($this->date.' 00:11:40'),strtotime($this->date.' 00:22:00')));
        $this->assertFalse($timer->inSameInterval(
            strtotime($this->date.' 23:58:00'),strtotime($this->date.' 23:49:00'))); 
    }

}
