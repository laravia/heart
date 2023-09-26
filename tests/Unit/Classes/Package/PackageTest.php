<?php

namespace Laravia\Heart\Tests\Unit\Classes\Package;

use Laravia\Heart\App\Classes\Package\Package;
use Laravia\Heart\App\Classes\TestCase;

class PackageTest extends TestCase
{

    public function __construct()
    {
        parent::__construct();
        $this->class = new (Package::class);
    }

    public function testInitClass()
    {
        $this->assertClassExist($this->class::class);
    }

    public function testAll()
    {
        $this->assertMethodInClassExist($this->class::class, 'all');
    }

    public function testGetByName()
    {
        $this->assertMethodInClassExist($this->class::class, 'getByName');
        $this->assertIsArray($this->class->getByName('heart'));
    }

}
