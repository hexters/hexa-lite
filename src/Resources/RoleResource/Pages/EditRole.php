<?php

namespace Hexters\HexaLite\Resources\RoleResource\Pages;

use Hexters\HexaLite\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(fn() => hexa()->can('role.delete')),
        ];
    }

    public static function canAccess(array $parameters = []): bool
    {
        return hexa()->can('role.update');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['gates'] = $data['access'];

        return $data;
    }


    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['access'] = $data['gates'] ?? [];

        return $data;
    }
}
