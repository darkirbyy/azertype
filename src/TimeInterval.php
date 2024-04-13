<?php

namespace Azertype;

class TimeInterval{

   /* function __construct(){
        date_default_timezone_set("Europe/Paris");
    }*/

    static function getCurrentTimestamp() : int{
        date_default_timezone_set("Europe/Paris");
        return time();
    }

    static function getNearestInterval(int $timestamp, int $interval = Config::INTERVAL_BETWEEN_GAME) : ?int{
        date_default_timezone_set("Europe/Paris");
        if($timestamp <0 || $interval < 1 || $interval > 1440)
            return null;
        $timestampAtMidnight = strtotime(date("Y-m-d", $timestamp));
        $diff = $timestamp - $timestampAtMidnight;
        $diff -= $diff % ($interval * 60);
        return $timestampAtMidnight + $diff;
    }

    static function areInSameInterval(int $timestamp1, int $timestamp2, int $interval = Config::INTERVAL_BETWEEN_GAME) : bool{
        return TimeInterval::getNearestInterval($timestamp1, $interval)
         === TimeInterval::getNearestInterval($timestamp2, $interval);
    }


}