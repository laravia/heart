<?php

namespace Laravia\Heart\Tests\Unit\Classes;

use Laravia\Heart\App\Classes\TestCase;
use Laravia\Heart\App\Console\Commands\PackageCloneWithSearchAndReplace;

class PackageCloneWithSearchAndReplaceTest extends TestCase
{
    public function testInitClass()
    {
        $this->assertClassExist(PackageCloneWithSearchAndReplaceTest::class);
    }

    public function testRunCommand()
    {
        $this->artisan('laravia:package:clone vendor/laravia/heart heart core')
            ->expectsOutput('PackageCloneWithSearchAndReplace = done! all cloned into: /var/www/html/storage/framework/tmp/vendor/laravia/core')
            ->assertExitCode(0);
    }

}
