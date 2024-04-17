<?php
namespace Azertype\Fixture;

class GeneratorFixture{
    const HERO_ONEWORD = '["éléphantiasiques"]';
    const HERO_FIVEWORDS = '["hennissant","déboulonné","légitimité","accoutrasse","coffrerez"]';
    const HERO_XSSWORDS = '["<script>function a(a","b){alert(1",")}a(1","2)</script>"]';
    const FAKE_FIVEWORD = 'aaa,ddd,fff,yyy,ddd';

}