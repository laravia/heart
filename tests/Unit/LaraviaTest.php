<?php

namespace Laravia\Heart\Tests\Unit;

use Laravia\Heart\App\Classes\TestCase as LaraviaTestCase;
use Laravia\Heart\App\Laravia;

class LaraviaTest extends LaraviaTestCase
{

    public function testInitClass()
    {
        $this->assertClassExist(Laravia::class);
    }

    public function testGetConfig()
    {
        $this->assertIsArray(Laravia::config());
    }

    public function testConfigVariables()
    {
        $this->assertIsString(Laravia::config('heart.version'));
        $this->assertIsString(Laravia::config('heart.name'));
        $this->assertIsString(Laravia::config('heart.github'));
        $this->assertIsString(Laravia::config('heart.homepage'));
    }

    public function testGetCommands()
    {
        $this->assertIsArray(Laravia::commands());
    }

    /**
     * @dataProvider dataProviderUrls
     * */
    public function testLaraviaFunctionGetDomainNameWithoutSuburl($insert = "", $expects = "")
    {
        $domain = Laravia::getDomainNameWithoutSuburl($insert);
        $this->assertEquals($domain, $expects);
    }

    public static function dataProviderUrls()
    {
        yield 'default suburl www' => [
            'insert' => 'www.orf.at',
            'expects' => 'orf.at',
        ];
        yield 'different suburl' => [
            'insert' => 'test.orf.at',
            'expects' => 'orf.at',
        ];
        yield 'localhost' => [
            'insert' => 'localhost',
            'expects' => 'localhost',
        ];
    }
    public static function dataProviderUrlsWithoutExtension()
    {
        yield 'default suburl www' => [
            'insert' => 'www.orf.at',
            'expects' => 'orf',
        ];
        yield 'without' => [
            'insert' => 'orf.at',
            'expects' => 'orf',
        ];
        yield 'different suburl' => [
            'insert' => 'test.orf.at',
            'expects' => 'orf',
        ];
        yield 'localhost' => [
            'insert' => 'localhost',
            'expects' => 'localhost',
        ];
    }

    public function testGetOrderdConfig()
    {
        $this->assertIsArray(Laravia::getOrderdConfig());
    }

    public function testLinksMethod()
    {
        $this->assertIsArray(Laravia::links());
    }
}
