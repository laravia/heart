<?php

namespace Laravia\Heart\Tests\Unit\Classes;

use Laravia\Heart\App\Classes\Composer;
use Laravia\Heart\App\Classes\TestCase;

class ComposerTest extends TestCase
{

    public function testInitClass()
    {
        $this->assertClassExist(Composer::class);
    }

    public function testParse()
    {
        $composer = new Composer();
        $composer->parse();
        $this->assertGreaterThanOrEqual(1, sizeof($composer->packages));
    }

    public function testGetFilesByKey()
    {
        $composer = new Composer();
        $composer->parse();
        $this->assertGreaterThanOrEqual(1, sizeof($composer->getFilesByKey('config')));
    }
}
