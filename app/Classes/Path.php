<?php

namespace Laravia\Heart\App\Classes;

class Path
{

    public $path;
    public $packages;

    public function __construct()
    {
        $composer = new Composer;
        $composer->parse();
        $this->packages = $composer->packages;
    }

    function get($name)
    {
        return data_get($this->packages, $name . '.path');
    }
}
