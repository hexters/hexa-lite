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
        if (request()->user()->is_superadmin) {
            return true; // superadmin's should have root access no?
        }
        return Gate::allows($permissions) ?? false;
    }
}
