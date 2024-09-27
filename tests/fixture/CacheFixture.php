<?php
namespace Tests\fixture;

class CacheFixture{
    const FILENAME = 'foo';
    const DRAW_GOOD_ARRAY = array('game_id' => 8, 'validity' =>1713357120, 'words' => 'aaa,bbb');
    const DRAW_GOOD_JSON = '{"game_id":8,"validity":1713357120,"words":"aaa,bbb"}';
    const DRAW_WRONG_JSON = '{game_id":2,"words":"aaa,bbb"}';
    const DRAW_OTHER_ARRAY = array('game_id' => 1, 'validity' =>1713354680, 'words' => 'ccc,ddd');
    const DRAW_OTHER_JSON = '{"game_id":1,"validity":1713354680,"words":"ccc,ddd"}';
    const SCORE_GOOD_ARRAY = array('game_id' => 8, 'best_time' => 2500, 'nb_players' => 8);
    const SCORE_GOOD_JSON = '{"game_id":8,"best_time":2500,"nb_players":8}';
}