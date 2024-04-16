<?php
$test = '["test","lol",]';
if(preg_match('/^\[("[a-zéèçàù-]{1,}",?){1,}]$/iu', $test))
    echo 'bon';
if($test[-2] === ',')
{
    $test[-2] = ']';
    $test[-1] = ' ';
}
echo print_r(json_decode($test));