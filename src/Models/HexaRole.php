<?php

namespace Hexters\HexaLite\Models;

use Hexters\HexaLite\Traits\UlidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HexaRole extends Model
{
    use HasFactory, UlidGenerator;

    protected $fillable = [
        'ulid',
        'name',
        'permissions',
        'type',
        'state',
    ];

    protected $casts = [
        'permissions' => 'array'
    ];

    public function admins()
    {
        return $this->belongsToMany(HexaAdmin::class, 'hexa_role_admin', 'hexa_role_id', 'hexa_admin_id');
    }
}
