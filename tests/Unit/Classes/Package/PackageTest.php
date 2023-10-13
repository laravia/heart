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
        $this->assertMethodInClassExists('all',$this->class);
    }

    public function testGetByName()
    {
        $this->assertMethodInClassExists('getByName',$this->class);
        $this->assertIsArray((new $this->class())->getByName('heart'));
    }

}
