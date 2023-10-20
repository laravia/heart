<?php

namespace Laravia\Heart\Tests\Unit\Classes;

use Carbon\Carbon;
use Laravia\Heart\App\Classes\Parser;
use Laravia\Heart\App\Classes\TestCase;
use Illuminate\Support\Str;
use Laravia\Heart\App\Laravia;

class ParserTest extends TestCase
{
    public function testInitClass()
    {
        $this->assertClassExist(Parser::class);
    }

    public function testParser()
    {
        $parsedownTestText = Str::parsedown('**bold**');
        $this->assertNotEquals('**bold**', $parsedownTestText);
    }
    public function testParserTextDate()
    {
        $dateNow = Carbon::now()->format(Laravia::config('core.dateFormat'));
        $parsedownTestText = Str::parsedown('[[date:now]]', ['html' => false]);
        $this->assertEquals($dateNow, $parsedownTestText);
    }
    public function testDateTime()
    {
        $dateNow = Carbon::now()->format(Laravia::config('core.dateTimeFormat'));
        $parsedownTestText = Str::parsedown('[[datetime:now]]', ['html' => false]);
        $this->assertEquals($dateNow, $parsedownTestText);
    }
    public function testLink()
    {
        $parsedownTestText = Str::parsedown('[google](https://www.google.com)');
        $this->assertNotEquals('[google](https://www.google.com)', $parsedownTestText);
    }

    public function testParserPre()
    {
        $parsedownTestText = Str::parsedown('```
        echo "Hello World";
        ```');
        $this->assertNotEquals('```
        echo "Hello World";
        ```', $parsedownTestText);
    }

}
