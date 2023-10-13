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
        $this->assertMethodInClassExists('handle', Call::class);
    }

    public function testInstallMethodExists()
    {
        $this->assertMethodInClassExists('install', Call::class);
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
