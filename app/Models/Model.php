<?php

namespace Laravia\Heart\App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model as LaravelDefaultModel;

class Model extends LaravelDefaultModel
{
    public $dateTimeFormat = 'd.m.Y H:i';

    public function getDateTimeFormat(){
        return $this->dateTimeFormat;
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ($value ? date($this->dateTimeFormat, strtotime($value)) : null),
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ($value ? date($this->dateTimeFormat, strtotime($value)) : null),
        );
    }

    protected function active(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => ($value ? 1 : null),
        );
    }
}
