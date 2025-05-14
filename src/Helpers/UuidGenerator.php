<?php

namespace Hexters\HexaLite\Helpers;

use Illuminate\Support\Str;

trait UuidGenerator
{

    protected static function bootUuidGenerator()
    {
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid7();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
