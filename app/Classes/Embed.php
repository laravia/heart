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
        return '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $this->code . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
    }

    public function soundcloud()
    {
        return '<iframe width="560" height="166" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/' . $this->code . '&color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true"></iframe>';
    }

    public function mixcloud()
    {
        return '<iframe width="560" height="120" src="https://www.mixcloud.com/widget/iframe/?hide_cover=1&feed=' . $this->code . '" frameborder="0" ></iframe>';
    }

    public function icon()
    {
        return '<i class="fa-solid fa-' . $this->code . '"></i>';
    }
}
