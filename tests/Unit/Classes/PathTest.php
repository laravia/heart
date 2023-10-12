<?php

namespace Laravia\Heart\Tests\Unit\Classes;

use Laravia\Heart\App\Classes\Path;
use Laravia\Heart\App\Classes\TestCase;

class PathTest extends TestCase
{
    public function testInitClass()
    {
        $this->assertClassExist(Path::class);
    }

    public function testGetPathForLaravia(): void
    {
        $path = new Path;
        $this->assertStringContainsString('heart', $path->get('heart'));
    }
}
