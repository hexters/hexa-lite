<?php

namespace Hexters\HexaLite\Resources\AdminResource\Pages;

use Hexters\HexaLite\Resources\AdminResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditAdmin extends EditRecord
{
    protected static string $resource = AdminResource::class;

    /**
     * @param  array<string, mixed>  $parameters
     */
    public static function canAccess(array $parameters = []): bool
    {
        return hexa()->can('hexa.admin.edit');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(hexa()->can('hexa.admin.delete'))
                ->before(function ($action, $record) {
                    if ($record->id == auth()->id()) {
                        Notification::make()
                            ->danger()
                            ->title(__("Can't Deleted"))
                            ->body(__("You can't delete your account yourself!"))
                            ->persistent()
                            ->send();
                        $action->cancel();
                    }

                    if ($record?->is_superadmin) {
                        Notification::make()
                            ->danger()
                            ->title(__("Can't Deleted"))
                            ->body(__("You cannot delete a superadmin account!"))
                            ->persistent()
                            ->send();
                        $action->cancel();
                    }
                }),
        ];
    }
}
