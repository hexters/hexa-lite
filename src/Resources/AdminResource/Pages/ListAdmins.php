<?php

namespace Hexters\HexaLite\Resources\AdminResource\Pages;

use Hexters\HexaLite\Resources\AdminResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdmins extends ListRecords
{
    protected static string $resource = AdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(hexa()->can('hexa.admin.create')),
        ];
    }
}
