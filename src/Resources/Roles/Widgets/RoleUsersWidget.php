<?php

namespace Hexters\HexaLite\Resources\Roles\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;

class RoleUsersWidget extends BaseWidget
{
    public ?Model $record = null;

    protected int|string|array $columnSpan = 'full';

    public function getTableHeading(): string
    {
        return __('Users with this Role');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => $this->record?->users() ?? config('hexa.models.user')::query()->whereRaw('1 = 0'))
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label(__('Name')),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->label(__('Email')),
                TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime('d/m/Y H:i')
                    ->label(__('Registered At')),
            ])
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5)
            ->emptyStateHeading(__('No users assigned'))
            ->emptyStateDescription(__('No users have been assigned to this role yet.'));
    }
}
