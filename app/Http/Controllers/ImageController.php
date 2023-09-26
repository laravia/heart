<?php

namespace Laravia\Heart\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Laravia\Heart\App\Classes\Image\ImageDummy;

class ImageController extends Controller
{

    public function createDummy($width = 500, $height = 500, $color = '#ccc'): Response
    {
        $image = new ImageDummy(width: $width, height: $height, color: $color);
        return $image->make();
    }
}
