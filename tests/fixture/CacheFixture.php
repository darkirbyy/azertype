<?php
namespace Tests\fixture;

class CacheFixture{
    const FILENAME = 'foo';
    const GOOD_ARRAY = array('game_id' => 8, 'validity' =>1713357120, 'words' => 'aaa,bbb');
    const GOOD_JSON = '{"game_id":8,"validity":1713357120,"words":"aaa,bbb"}';
    const WRONG_JSON = '{game_id":2,"words":"aaa,bbb"}';
    const OTHER_JSON = '{"game_id":1,"validity":1713354680,"words":"ccc,ddd"}';
}