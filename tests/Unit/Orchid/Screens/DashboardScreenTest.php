<?php

namespace Laravia\Counter\Tests\Unit\App;

use Laravia\Heart\App\Classes\TestCase;
use Laravia\Heart\App\Classes\TestScreenCaseTrait;
use Laravia\Heart\App\Orchid\Screens\Dashboard;

class DashboardScreenTest extends TestCase
{

    use TestScreenCaseTrait;
    public function getScreenTestClass()
    {
        return new Dashboard();
    }

}
