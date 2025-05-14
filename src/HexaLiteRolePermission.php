<?php

namespace Hexters\HexaLite;

use Hexters\HexaLite\Models\HexaRole;

trait HexaLiteRolePermission
{
    public function roles()
    {
        return $this->belongsToMany(config('hexa.models.role'), 'hexa_role_user', 'user_id', 'role_id')
            ->where('guard', hexa()->guard());
    }
}
