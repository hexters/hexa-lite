<?php

namespace Hexters\HexaLite\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HexaOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'option_key',
        'option_value',
    ];
}
