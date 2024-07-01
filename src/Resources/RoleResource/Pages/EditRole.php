<?php

namespace Hexters\HexaLite\Resources\RoleResource\Pages;

use Hexters\HexaLite\Resources\RoleResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return hexa()->can('hexa.role.and.permission.edit');
    }

    protected function beforeSave(): void
    {


        if ($this->getRecord()->id == 1 && in_array($this->data['state'], ['inactive'])) {

            Notification::make()
                ->danger()
                ->title(__("Can't Change"))
                ->body(__("This role cannot be inactivated!"))
                ->persistent()
                ->send();

            $this->halt();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(hexa()->can('hexa.role.and.permission.delete'))
                ->before(function ($action, $record) {
                    if ($record->admins()->count() > 0) {
                        Notification::make()
                            ->danger()
                            ->title(__("Can't Deleted"))
                            ->body(__("Role cannot be deleted because it is still used by another account!"))
                            ->persistent()
                            ->send();
                        $action->cancel();
                    }
                }),
        ];
    }
}
