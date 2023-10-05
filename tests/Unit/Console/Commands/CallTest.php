<?php

namespace Laravia\Heart\Tests\Unit\Classes;

use Illuminate\Support\Facades\Artisan;
use Laravia\Heart\App\Console\Commands\Install;
use Laravia\Heart\App\Classes\TestCase;
use Laravia\Heart\App\Console\Commands\Call;

class CallTest extends TestCase
{
    public function testInitClass()
    {
        $this->assertClassExist(Call::class);
    }

    public function testHandle()
    {
        $this->assertMethodInClassExist(Call::class, 'handle');
    }

    public function testInstallMethodExists()
    {
        $this->assertMethodInClassExist(Call::class, 'install');
    }

    public function testInstall()
    {
        Artisan::shouldReceive('call')
            ->once()
            ->with('laravia:call')
            ->andReturn('all commands called');

        Artisan::call('laravia:call');
    }
}
