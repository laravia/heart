<?php

namespace Laravia\Heart\App\Classes\Package;

use Laravia\Heart\App\Classes\Composer;

class Package
{
    public $name;

    public function all()
    {
        $composer = new Composer();
        return $composer->getPackages();
    }

    public function getByName($name)
    {
        $composer = new Composer();
        return $composer->getPackage($name);
    }

}
