<?php

namespace Laravia\Heart\Tests\Unit\Classes;

use Illuminate\Support\Facades\Artisan;
use Laravia\Heart\App\Console\Commands\Publish;
use Laravia\Heart\App\Classes\TestCase;

class PublishTest extends TestCase
{
    public function testInitClass()
    {
        $this->assertClassExist(Publish::class);
    }

    public function testPublish()
    {
        Artisan::shouldReceive('call')
            ->once()
            ->with('laravia:publish')
            ->andReturn('all files published');

        Artisan::call('laravia:publish');
    }

    public function testPublishForce()
    {
        Artisan::shouldReceive('call')
            ->once()
            ->with('laravia:publish true')
            ->andReturn('all files published');

        Artisan::call('laravia:publish true');
    }
}
