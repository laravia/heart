<?php

namespace Laravia\Heart\Tests\Unit\Classes;

use Laravia\Heart\App\Classes\TestCase;
use Laravia\Heart\App\Console\Commands\Laravia;

class LaraviaTest extends TestCase
{
    public function testInitClass()
    {
        $this->assertClassExist(Laravia::class);
    }

    public function testRunCommand()
    {
        $this->artisan('laravia')
            ->expectsOutput('############## LARAVIA ################')
            ->assertExitCode(0);
    }

}
