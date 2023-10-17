<?php

namespace Laravia\Heart\Tests\Unit;

use Laravia\Heart\App\Classes\TestCase as LaraviaTestCase;
use Laravia\Heart\App\Laravia;
use Spatie\Tags\Tag;

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

    public function testDashboardMetrics()
    {
        $this->assertIsArray(Laravia::dashboardMetrics());
    }

    public function testUuid()
    {
        $this->assertIsString(Laravia::uuid());
    }

    public function testInfo()
    {
        $this->assertIsString(Laravia::info());
    }

    public function testGetAllDataFromConfigByKey($key = "config")
    {
        $this->assertIsArray(Laravia::getDataFromConfigByKey($key));
    }

    public function testIsInitialCall()
    {
        $this->assertFalse(Laravia::isInitialCall('backend'));
        $this->call('GET', config('platform.prefix') . '/dashboard', [], [], [], ['HTTP_REFERER' => 'login']);
        $this->assertTrue(Laravia::isInitialCall('backend'));
        $this->call('GET', config('platform.prefix') . '/dashboard', [], [], [], ['HTTP_REFERER' => 'test']);
        $this->assertFalse(Laravia::isInitialCall('backend'));
    }

    public function testGetAllPackageNames()
    {
        $this->assertIsArray(Laravia::getAllPackageNames());
    }

    public function testIsNewEntry()
    {
        $this->assertTrue(Laravia::isNewEntry());
        request()->merge(['id' => 1]);
        $this->assertFalse(Laravia::isNewEntry());
    }

    public function testGetTagsFromOrchidTagAsArray()
    {
        $this->assertIsArray(Laravia::getSpatieTagsFromOrchidRequest(['test1', 'test2']));
    }

    public function testGetTagsFromOrchidTagAsArrayWithNewAndOld()
    {
        $tagText[] = 'test1';
        $tagText[] = 'test2';
        $tagText[] = 'test3';
        $tagText[] = 'test4';
        $tags[] = Tag::findOrCreate($tagText[0])->id;
        $tags[] = Tag::findOrCreate($tagText[1])->id;
        $tags[] = $tagText[2];
        $tags[] = $tagText[3];
        $tags = Laravia::getSpatieTagsFromOrchidRequest($tags);

        $this->assertNotContains([1, 2], $tags);
        $this->assertContains($tagText[0], $tags);
        $this->assertContains($tagText[1], $tags);
        $this->assertContains($tagText[2], $tags);
        $this->assertContains($tagText[3], $tags);
    }
}
