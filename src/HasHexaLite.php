<?php

namespace Hexters\HexaLite;

use Illuminate\Support\Str;

trait HasHexaLite
{

    public function defineGates(): array
    {
        return [];
    }

    public function roleName()
    {
        return method_exists(__CLASS__, 'getModelLabel') ? static::getModelLabel() : Str::of(collect(explode('\\', get_class()))->last())->headline();
    }
    
    public function gateIndexs()
    {
        return array_keys($this->defineGates());
    }
}
