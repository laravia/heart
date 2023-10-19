<?php

namespace Laravia\Heart\Tests\Unit\Classes\Traits;

use Laravia\Heart\App\Classes\TestCase;
use Laravia\Heart\App\Traits\ServiceProviderTrait;

class ServiceProviderTraitTest extends TestCase
{

    public function testInitClass()
    {
        $this->assertTraitExist(ServiceProviderTrait::class);
    }

}
