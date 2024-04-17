<?php

namespace Azertype\Helper;

/**
 * Given a timezone, a reset time, and a interval, 
 * divide the day in segment of length = interval time and start = reset time
 * For any timestamp, round it to the closest segment
 * (up or down with ceil or floor) or check if two timestamp are in the same segment
 */ 

class Timer{
    private string $reset;
    private string $interval;

    /**
     * Initialize the timer with the reset time and interval
     * 
     * @param ?string $time The reset time in 'HH:ii:ss' format 
     * @param ?string $interval The interval time in 'HH:ii:ss' format 
     */

    function __construct(?string $reset = null, ?string $interval = null) {
        $this->reset = $this->timeValid($reset, false) ? $reset : $_ENV['TIME_RESET']; 
        $this->interval = $this->timeValid($interval, true) ? $interval : $_ENV['TIME_INTERVAL']; 
    }

    /**
     * Check if a time is valid
     * 
     * @param string $time The time in 'HH:ii:ss' format 
     * @param bool  $allow24 Is the time '24:00:00' consider valid
     * 
     * @return bool
     */
    function timeValid(?string $time, bool $allow24 = false) : bool {
        if($time === null)
            return false;
        [$h, $m, $s] = explode(':', $time);
        if($allow24 && $h == 24 && $m == 0 && $s == 0)
            return true;
        elseif($h % 24 == $h && $m % 60 == $m && $s % 60 == $s)
            return true;
        else
            return false;
    }

    /**
     * Convert the interval into a timestamp in seconds
     * 
     * @return int
     */
    function intervalTimestamp() : int {
        [$h, $m, $s] = explode(':', $this->interval);
        $intervalTimestamp = (int)$s + (int)$m * 60 + (int)$h * 3600;
        return $intervalTimestamp;
    }

    /**
     * Given a timestamp, find the reset time (this day or the previous one)
     * and convert it to timestamp
     * 
     * @param int $currentTimestamp The  
     * 
     * @return int
     */
    function resetTimestamp(int $currentTimestamp) : int {
        $resetTimestamp = strtotime(date('Y-m-d ', $currentTimestamp).$this->reset);
        if($resetTimestamp > $currentTimestamp)
             $resetTimestamp -= 86400;
        return $resetTimestamp;
    }

    /**
     * Given a timestamp, find the first previous segment
     * and convert it to timestamp
     * 
     * @param int $currentTimestamp The  
     * 
     * @return ?int
     */
    function floorTimestamp(int $currentTimestamp) : ?int{
        if($currentTimestamp <0)
            return null;
        
        $intervalTimestamp = $this->intervalTimestamp();
        $resetTimestamp = $this->resetTimestamp($currentTimestamp);

        $diffTimestamp = $currentTimestamp - $resetTimestamp;
        $diffTimestamp -= ($diffTimestamp % $intervalTimestamp);
        $floorTimestamp =  $resetTimestamp + $diffTimestamp;
        return $floorTimestamp;
    }

    /**
     * Given a timestamp, find the first next segment
     * and convert it to timestamp
     * 
     * @param int $currentTimestamp The  
     * 
     * @return ?int
     */
    function ceilTimestamp(int $currentTimestamp) : ?int{
        if($currentTimestamp <0)
            return null;
        
        $intervalTimestamp = $this->intervalTimestamp();
        $resetTimestamp = $this->resetTimestamp($currentTimestamp);

        $diffTimestamp = $currentTimestamp - $resetTimestamp;
        $diffTimestamp -= ($diffTimestamp % $intervalTimestamp);
        $ceilTimestamp =  $resetTimestamp + $diffTimestamp + $intervalTimestamp;
        if($ceilTimestamp > $resetTimestamp + 86400)
            $ceilTimestamp = $resetTimestamp + 86400;
        return $ceilTimestamp;
    }

    /**
     * Given two timestamp, test if there are in the same segment of time
     * 
     * @param int $timestamp1 The first timestamp to compare  
     * @param int $timestamp2 The second timestamp to compare    
     * @return bool
     */
    function inSameInterval(int $timestamp1, int $timestamp2) : bool{
        return $this->floorTimestamp($timestamp1) === $this->floorTimestamp($timestamp2);
    }

}