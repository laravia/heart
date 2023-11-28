<?php

namespace Laravia\Heart\App\Classes;

class Embed
{
    public $code;

    public function parse($what, $code)
    {
        $this->code = $code;
        return $this->{$what}();
    }

    public function youtube()
    {
        return '<iframe width="100%" height="315" src="https://www.youtube.com/embed/' . $this->code . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
    }

    public function icon()
    {
        return '<i class="fa-solid fa-' . $this->code . '"></i>';
    }
}
