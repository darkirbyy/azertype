<?php
namespace Tests\fixture;

class HandlerFixture{
    const DRAW_GOOD_ARRAY = array('game_id' => 8, 'validity' =>1713357120, 'words' => 'aaa,bbb');
    const DRAW_GOOD_JSON = '{"game_id":8,"words":"aaa,bbb","wait_time":50}';
    const DRAW_WRONG_ARRAY = array('gameid' => 8, 'timestamp' =>1713357120);
    const SCORE_GOOD_ARRAY = array('game_id' => 8, 'best_time' => 2500, 'nb_players' => 11);
    const SCORE_GOOD_JSON = '{"game_id":8,"best_time":2500,"nb_players":11}';
    const SCORE_WRONG_ARRAY = array('gameid' => 8, 'best_time' => 2500, 'nb_players' => 52);
    const GOOD_TIME_BEFORE = 1713357070;
    const GOOD_TIME_AFTER = 1713357130;
}