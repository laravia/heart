<?php

namespace Laravia\Heart\App\Classes;


trait TestScreenCaseTrait
{

    public function testInitClass()
    {
        $this->assertClassExist($this->getScreenTestClass()::class);
    }

    public function testQuery()
    {
        $this->assertIsArray($this->getScreenTestClass()->query());
    }

    public function testName()
    {
        $this->assertIsString($this->getScreenTestClass()->name());
    }

    public function testDescription()
    {
        $this->assertIsString($this->getScreenTestClass()->description());
    }

    public function testCommandBar()
    {
        $this->assertIsArray($this->getScreenTestClass()->commandBar());
    }

    public function testLayout()
    {
        $this->assertIsArray($this->getScreenTestClass()->layout());
    }
}
