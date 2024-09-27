<?php
namespace Tests\fixture;

class GeneratorFixture{
    const HERO_ONEWORD = '["éléphantiasiques"]';
    const HERO_FIVEWORDS = '["hennissant","déboulonné","légitimité","accoutrasse","coffrerez"]';
    const HERO_XSSWORDS = '["<script>function a(a","b){alert(1",")}a(1","2)</script>"]';
    const FAKE_FIVEWORD = 'aaa,ddd,fff,yyy,ddd';
    const SELF_ONEWORD = [['word' => 'le']];
    const SELF_FIVEWORDS = [['word' => 'le'], ['word' => 'bonjour'], ['word' => 'test'], ['word' => 'salut'], ['word' => 'savoir']];
}