<?php

namespace Hexters\HexaLite\Helpers;

use Illuminate\Support\Str;
use Hexters\HexaLite\Models\HexaOption;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class HexaCall
{
    public function can($permissions)
    {
        return Gate::allows($permissions) ?? false;
    }
}
