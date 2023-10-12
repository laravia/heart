<?php

namespace Laravia\Heart\Tests\Unit\Classes\Traits;

use Laravia\Heart\App\Classes\TestCase;

class ServiceProviderTraitTest extends TestCase
{

    public function testInitClass()
    {
        $this->assertClassExist(ServiceProviderTraitTest::class);
    }

}
