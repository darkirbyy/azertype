<?php

namespace Azertype\Helper;
use Azertype\Config;

class TimeInterval{

   /* function __construct(){
        date_default_timezone_set("Europe/Paris");
    }*/

    static function currentTimestamp() : int{
        date_default_timezone_set(Config::TIMEZONE);
        return time();
    }

    static function floorInterval(int $timestamp, int $interval = Config::INTERVAL_BETWEEN_GAME) : ?int{
        date_default_timezone_set(Config::TIMEZONE);
        if($timestamp <0 || $interval < 1 || $interval > 1440)
            return null;
        $timestampAtMidnight = strtotime(date("Y-m-d", $timestamp));
        $diff = $timestamp - $timestampAtMidnight;
        $diff -= $diff % ($interval * 60);
        return $timestampAtMidnight + $diff;
    }

    static function ceilInterval(int $timestamp, int $interval = Config::INTERVAL_BETWEEN_GAME) : ?int{
        if($timestamp <0 || $interval < 1 || $interval > 1440)
            return null;
        return TimeInterval::floorInterval($timestamp, $interval) + $interval*60;
    }

    static function areInSameInterval(int $timestamp1, int $timestamp2, int $interval = Config::INTERVAL_BETWEEN_GAME) : bool{
        return TimeInterval::floorInterval($timestamp1, $interval)
         === TimeInterval::floorInterval($timestamp2, $interval);
    }


}