<?php

namespace Laravia\Heart\Tests\Unit\Classes\Package;

use Laravia\Heart\App\Classes\TestCase;

class PackageTest extends TestCase
{
    public $class='Laravia\Heart\App\Classes\Package\Package';

    public function testInitClass()
    {
        $this->assertClassExist($this->class);
    }

    public function testAll()
    {
        $this->assertMethodInClassExist($this->class, 'all');
    }

    public function testGetByName()
    {
        $this->assertMethodInClassExist($this->class, 'getByName');
        $this->assertIsArray((new $this->class())->getByName('heart'));
    }

}
