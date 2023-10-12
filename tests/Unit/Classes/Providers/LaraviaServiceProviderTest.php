<?php

namespace Laravia\Heart\Tests\Unit\Providers;

use Laravia\Heart\App\Classes\TestCase;
use Laravia\Heart\App\Providers\LaraviaServiceProvider;

class LaraviaServiceProviderTest extends TestCase
{

    public function testInitClass()
    {
        $this->assertClassExist(LaraviaServiceProvider::class);
    }

    public function testMenu()
    {
        $provider = new LaraviaServiceProvider($this->app);
        $this->assertIsArray($provider->menu());
    }
}
