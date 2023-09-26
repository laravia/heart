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

    public function testGetPathForLaravia()
    {
        $path = new Path;
        $this->assertStringContainsString('laravia/heart',$path->get('heart'));
    }

}
