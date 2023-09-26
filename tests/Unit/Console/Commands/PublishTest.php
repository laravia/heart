<?php

namespace Laravia\Heart\Tests\Unit\Classes;

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
        $this->artisan('laravia:publish')
            ->expectsOutput('all files published')
            ->assertExitCode(0);
    }

    public function testPublishForce()
    {
        $this->artisan('laravia:publish true')
            ->expectsOutput('all files published')
            ->assertExitCode(0);
    }
}
