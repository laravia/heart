<?php

namespace Laravia\Heart\App;

use Laravia\Heart\App\Classes\Package\Package;

class LaraviaPackage extends Laravia
{

    public static function getByName($name)
    {
        $package = new Package();
        return $package->getByName($name);
    }

    public static function all()
    {
        $package = new Package();
        return $package->all();
    }
}
