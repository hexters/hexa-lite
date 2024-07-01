<?php

namespace Hexters\HexaLite\Traits;

use Illuminate\Support\Str;
trait HexAccess
{

    public function getPermissionId(): ?string
    {
        if (property_exists(__CLASS__, 'permissionId')) {
            return static::$permissionId;
        }

        return null;
    }

    public function getTitlePermission(): ?string
    {
        if (property_exists(__CLASS__, 'titlePermission')) {
            return static::$titlePermission;
        } else if (property_exists(__CLASS__, 'navigationLabel')) {
            return static::getNavigationLabel();
        }

        return Str::of(class_basename(__CLASS__))->headline();
    }

    public function getDescriptionPermission(): ?string
    {
        if (property_exists(__CLASS__, 'descriptionPermission')) {
            return static::$descriptionPermission;
        }

        return null;
    }

    public function getSubPermissions(): array
    {
        if (property_exists(__CLASS__, 'subPermissions')) {
            return static::$subPermissions;
        }

        return [];
    }

    public function getKeySubPermissions(): array
    {
        if (property_exists(__CLASS__, 'subPermissions')) {
            return array_keys(static::$subPermissions);
        }

        return [];
    }
}
