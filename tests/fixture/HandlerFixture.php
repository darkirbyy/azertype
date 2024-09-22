<?php
namespace Tests\fixture;

class HandlerFixture{
    const DRAW_GOOD_ARRAY = array('game_id' => 8, 'validity' =>1713357120, 'words' => 'aaa,bbb');
    const DRAW_GOOD_JSON = '{"game_id":8,"words":"aaa,bbb","wait_time":50}';
    const DRAW_WRONG_ARRAY = array('gameid' => 8, 'timestamp' =>1713357120);
    const SCORE_GOOD_ARRAY = array('game_id' => 8, 'validity' => 1713357120, 'best_time' => 2500, 'nb_players' => 11);
    const SCORE_GOOD_JSON = '{"game_id":8,"best_time":2500,"nb_players":11}';
    const SCORE_EXPIRE_ARRAY = array('game_id' => 0, 'validity' => 1713357120, 'best_time' => 2500, 'nb_players' => 11);
    const SCORE_EXPIRE_JSON = '{"game_id":0,"best_time":2500,"nb_players":11}';
    const SCORE_WRONG_ARRAY = array('gameid' => 8, 'best_time' => 2500, 'nb_players' => 52);
    const POST_WRONG_JSON = '{"gameid":0,time":2000}';
    const POST_GOOD_JSON_EXPIRED = '{"game_id":7,"time":2000}';
    const POST_GOOD_JSON_INVALID = '{"game_id":8,"time":0}';
    const POST_GOOD_JSON_VALID = '{"game_id":8,"time":2000}';
    const POST_GOOD_ARRAY = array(2000, 12, 8);
    const GOOD_TIME_BEFORE = 1713357070;
    const GOOD_TIME_AFTER = 1713357130;
}