<?php

namespace Hexters\HexaLite\Resources;

use Hexters\HexaLite\Resources\AdminResource\Pages;
use Hexters\HexaLite\Resources\AdminResource\RelationManagers;
use App\Models\Admin;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Hexters\HexaLite\Models\HexaRole;
use Hexters\HexaLite\Traits\HexAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;


class AdminResource extends Resource
{

    use HexAccess;

    protected static ?string $permissionId = 'hexa.admin';

    protected static ?string $descriptionPermission = 'Admin can manage Admin account';

    protected static ?array $subPermissions = [
        'hexa.admin.create' => 'Can Create',
        'hexa.admin.edit' => 'Can Edit',
        'hexa.admin.delete' => 'Can Delete',
    ];

    public static function canAccess(): bool
    {
        return hexa()->can(static::$permissionId);
    }

    protected static ?int $navigationSort = 1000;

    protected static ?string $navigationGroup = 'Setting & Access';

    protected static ?string $recordTitleAttribute = 'name';

    protected static int $globalSearchResultsLimit = 5;

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'roles.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'E-Mail' => $record->email,
            'Roles' => $record->roles->pluck('name')->join(', '),
        ];
    }

    protected static ?string $model = Admin::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        Section::make('Account Info.')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required(),
                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->required(fn (string $context): bool => $context === 'create'),
                                Select::make('roles')
                                    ->multiple()
                                    ->required()
                                    ->relationship('roles', 'name')
                                    ->options(HexaRole::where('state', 'active')->get()->pluck('name', 'id'))
                                    ->searchable()
                            ]),
                        Section::make([
                            FileUpload::make('avatar_url')
                                ->directory('avatars')
                                ->label('Profile Photo')
                                ->image()
                                ->avatar()
                                ->imageEditor()
                                ->circleCropper(),
                            Forms\Components\Select::make('state')
                                ->label('Status')
                                ->options([
                                    'active' => 'Active',
                                    'inactive' => 'Inactive',
                                ]),
                        ])->columns([
                            'default' => 1,
                            'lg' => 2
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar_url')
                    ->circular()
                    ->defaultImageUrl('https://www.gravatar.com/avatar/123&s=200')
                    ->label('#')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                IconColumn::make('online_at')
                    ->label('Online')
                    ->icon('heroicon-o-wifi')
                    ->color(fn (Model $record) => $record->online_at > now() ? 'success' : 'gray')
                    ->sortable(),
                Tables\Columns\TextColumn::make('state')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->searchable(),
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
                    ->visible(hexa()->can('hexa.admin.edit')),
                Tables\Actions\DeleteAction::make()
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
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(hexa()->can('hexa.admin.delete')),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}
