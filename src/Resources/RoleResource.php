<?php

namespace Hexters\HexaLite\Resources;

use Hexters\HexaLite\Resources\RoleResource\Pages;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Hexters\HexaLite\HasHexaLite;

class RoleResource extends Resource
{
    use HasHexaLite;

    public static function getModel(): string
    {
        return config('hexa.models.role');
    }

    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';

    protected static bool $shouldRegisterNavigation = false;

    public $hexaSort = 1;

    public function defineGates()
    {
        return [
            'role.index'  => __('Access Roles & Permissions'),
            'role.create' => __('Create New Role and Permission'),
            'role.update' => __('Update Existing Role and Permission'),
            'role.delete' => __('Delete Role and Permission'),
        ];
    }

    public static function canAccess(): bool
    {
        return hexa()->can('role.index');
    }

    public static function getModelLabel(): string
    {
        return __('Role & Permissions');
    }

    public static function form(Form $form): Form
    {

        $permissions = collect(config('hexa-lite-roles'))
            ->map(function ($role) {
                return Section::make($role['name'])
                    ->collapsed(false)
                    ->schema([
                        CheckboxList::make('gates')
                            ->searchable()
                            ->columns(2)
                            ->hiddenLabel()
                            ->bulkToggleable()
                            ->options($role['names']),
                    ]);
            });

        return $form
            ->columns(1)
            ->schema([
                TextInput::make('name')
                    ->label(__('Role Name'))
                    ->maxLength(100)
                    ->placeholder(__('Supervisor'))
                    ->required(),
                ViewField::make('checkall')
                    ->label(__('Check / Uncheck all'))
                    ->view('hexa::role.toggle-button'),
                ...$permissions,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->where('guard', hexa()->guard()))
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label(__('Role Name')),
                TextColumn::make('created_by_name')
                    ->searchable()
                    ->label(__('Crated By')),
                TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime('d/m/y H:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn() => hexa()->can('role.update')),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn() => hexa()->can('role.delete')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn() => hexa()->can('role.delete')),
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
