<?php

namespace Azertype\Helper;

class Timer{

    static function currentTimestamp() : int{
        date_default_timezone_set($_ENV['HELPER_TIMER_TIMEZONE']);
        return time();
    }

    static function floorInterval(int $timestamp, ?int $interval = null) : ?int{
        $interval ??= $_ENV['HELPER_TIMER_DEFAULTINTERVAL']; 
        date_default_timezone_set($_ENV['HELPER_TIMER_TIMEZONE']);
        if($timestamp <0 || $interval < 1 || $interval > 1440)
            return null;
        $timestampAtMidnight = strtotime(date("Y-m-d", $timestamp));
        $diff = $timestamp - $timestampAtMidnight;
        $diff -= $diff % ($interval * 60);
        return $timestampAtMidnight + $diff;
    }

    static function ceilInterval(int $timestamp, ?int $interval = null) : ?int{
        $interval ??= $_ENV['HELPER_TIMER_DEFAULTINTERVAL']; 
        if($timestamp <0 || $interval < 1 || $interval > 1440)
            return null;
        return Timer::floorInterval($timestamp, $interval) + $interval*60;
    }

    static function areInSameInterval(int $timestamp1, int $timestamp2, ?int $interval = null) : bool{
        $interval ??= $_ENV['HELPER_TIMER_DEFAULTINTERVAL']; 
        return Timer::floorInterval($timestamp1, $interval)
         === Timer::floorInterval($timestamp2, $interval);
    }

}