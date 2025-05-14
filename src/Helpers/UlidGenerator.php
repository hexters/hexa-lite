<?php

namespace Hexters\HexaLite\Helpers;

use Illuminate\Support\Str;

trait UlidGenerator
{

    protected static function bootUlidGenerator()
    {
        static::creating(function ($model) {
            $model->ulid = (string) Str::ulid();
        });
    }

    public function getRouteKeyName()
    {
        return 'ulid';
    }
}
