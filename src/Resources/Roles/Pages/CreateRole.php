<?php

namespace Hexters\HexaLite\Resources\Roles\Pages;

use Filament\Resources\Pages\CreateRecord;
use Hexters\HexaLite\Resources\Roles\RoleResource;
use Illuminate\Support\Facades\Auth;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return hexa()->can('role.create');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['access'] = $data['gates'] ?? [];
        $data['guard'] = hexa()->guard();
        $data['created_by_name'] = Auth::user()->name;

        return $data;
    }
}
