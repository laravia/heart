<?php

namespace Laravia\Heart\Tests\Unit\Classes;

use Laravia\Heart\App\Classes\Embed;
use Laravia\Heart\App\Classes\TestCase;

class EmbedTest extends TestCase
{
    public function testInitClass()
    {
        $this->assertClassExist(Embed::class);
    }

    /**
     * @dataProvider dataProviderPortals
     * */
    public function testParse($what, $code, $expects)
    {
        $embed = app(Embed::class);
        $embedCode = $embed->parse($what, $code);
        $this->assertStringContainsString($expects, $embedCode);
    }

    public static function dataProviderPortals()
    {
        yield 'youtube' => [
            'insert' => 'youtube',
            'code' => 'v0Lc-OImXxs',
            'expects' => 'iframe',
        ];
    }
}
