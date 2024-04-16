<?php
namespace Azertype\Fixture;

class CacheFixture{
    const FILENAME = 'foo';
    const GOOD_ARRAY = array('game_id' => 2, 'words' => 'aaa,bbb');
    const GOOD_JSON = '{"game_id":2,"words":"aaa,bbb"}';
    const WRONG_JSON = '{game_id":2,"words":"aaa,bbb"}';
    const OTHER_JSON = '{game_id":3,"timestamp":1684485343}';

}