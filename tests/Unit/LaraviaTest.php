<?php

namespace Laravia\Heart\Tests\Unit;

use Laravia\Heart\App\Classes\TestCase as LaraviaTestCase;
use Laravia\Heart\App\Laravia;
use Orchid\Platform\Models\User;

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

    /**
     * @dataProvider dataProviderUrlsWithoutExtension
     * */
    public function testLaraviaFunctionGetProjectNameFromDomain($insert = "", $expects = "")
    {
        $domain = Laravia::getProjectNameFromDomain($insert);
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

    public function testGetArrayWithDistinctFieldDataFromClassByKey()
    {
        User::create([
            'name' => 'test',
            'email' => 'test1@test.com',
            'password' => 'test1'
        ]);
        User::create([
            'name' => 'test',
            'email' => 'test2@test.com',
            'password' => 'test2'
        ]);
        User::create([
            'name' => 'test2',
            'email' => 'test3@test.com',
            'password' => 'test2'
        ]);


        $select = Laravia::getArrayWithDistinctFieldDataFromClassByKey(User::class, 'name');
        $this->assertIsArray($select);
        $this->assertEquals(2, sizeof($select));
    }
}
