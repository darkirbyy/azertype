<?php
namespace Tests\Fixture;

class HandlerFixture{
    const GOOD_ARRAY = array('game_id' => 8, 'validity' =>1713357120, 'words' => 'aaa,bbb');
    const GOOD_JSON = '{"game_id":8,"words":"aaa,bbb","wait_time":50}';
    const GOOD_TIME_BEFORE = 1713357070;
    const GOOD_TIME_AFTER = 1713357130;
    const WRONG_ARRAY = array('gameid' => 8, 'timestamp' =>1713357120);
}