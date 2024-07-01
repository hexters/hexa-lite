<?php

namespace Hexters\HexaLite\Traits;

use Illuminate\Support\Str;

trait UlidGenerator
{

    protected static function bootUlidGenerator()
    {
        static::creating(function ($model) {
            $model->ulid = (string) Str::ulid();
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'ulid';
    }
}
