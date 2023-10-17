<?php

namespace Laravia\Heart\App\Models;

use Illuminate\Database\Eloquent\Model as LaravelDefaultModel;

class Model extends LaravelDefaultModel
{
    public $dateTimeFormat = 'd.m.Y H:i';

    public function getDateTimeFormat(){
        return $this->dateTimeFormat;
    }


}
