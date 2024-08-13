<?php

namespace Hexters\HexaLite\Resources;

use Hexters\HexaLite\Resources\RoleResource\Pages;
use Hexters\HexaLite\Resources\RoleResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Hexters\HexaLite\Forms\Components\Permission;
use Hexters\HexaLite\Models\HexaRole;
use Hexters\HexaLite\Traits\HexAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoleResource extends Resource
{
    use HexAccess;

    protected static ?string $permissionId = 'hexa.role.and.permission';

    protected static ?string $descriptionPermission = 'Admin can manage Role & Permission';

    /**
     * Optional Section
     */
    protected static ?array $subPermissions = [
        'hexa.role.and.permission.create' => 'Can Create',
        'hexa.role.and.permission.edit' => 'Can Edit',
        'hexa.role.and.permission.delete' => 'Can Delete',
    ];

    public static function canAccess(): bool
    {
        return hexa()->can(static::$permissionId);
    }

    protected static ?string $recordTitleAttribute = 'name';

    protected static int $globalSearchResultsLimit = 5;

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Used' => $record->admins->count() . ' account'
        ];
    }

    protected static ?int $navigationSort = 1100;

    protected static ?string $navigationGroup = 'Setting & Access';

    protected static ?string $model = HexaRole::class;

    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';

    protected static ?string $navigationLabel = 'Role & Permissions';

    protected static ?string $modelLabel = 'Role';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Role Details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Role Name')
                            ->placeholder('Role Name')
                            ->unique(ignoreRecord: true),
                        Select::make('state')
                            ->label('Status')
                            ->required()
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ]),
                    ])->columns(2),

                Section::make()
                    ->schema([
                        Permission::make('permissions')
                            ->label('List of Permissions')
                            ->required()
                    ])->columns(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('state')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(hexa()->can('hexa.role.and.permission.edit')),
                Tables\Actions\DeleteAction::make()
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(hexa()->can('hexa.role.and.permission.delete')),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
