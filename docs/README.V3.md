# Filament V4 & Hexa Lite V3

**Filament Hexa Lite** is a free and developer-friendly **role and permission management plugin** for [FilamentPHP V4](https://filamentphp.com/).  
It helps you manage user roles and access permissions across Resources, Pages, and Widgets — with support for multi-panel apps via custom guards.

Currently in version 3, Hexa Lite is more intuitive, customizable, and production-ready.

![Banner](https://github.com/hexters/assets/blob/main/hexa/v2/banner.png?raw=true)

## Version Docs.

|Version|Filament|Doc.|
|:-:|:-:|-|
|V1|V3|[Read Doc.](https://github.com/hexters/hexa-lite/blob/main/docs/README.V1.md)|
|V2|V3|[Read Doc.](https://github.com/hexters/hexa-lite/blob/main/docs/README.V2.md)|
|V3|V4|[Read Doc.](https://github.com/hexters/hexa-lite/blob/main/README.md)|
    
## Installation

Install the package via Composer:

```bash
composer require hexters/hexa-lite
````

Run the database migration:

```bash
php artisan migrate
```

Register the plugin in your Filament panel:

```php
use Filament\Panel;
use Hexters\HexaLite\HexaLite;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            HexaLite::make(),
        ]);
}
```

Apply the trait to your `User` model:

```php
use Hexters\HexaLite\HexaLiteRolePermission;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HexaLiteRolePermission;
}
```


## Adding Role Selection

To allow role assignment via the admin panel, add a select input to your `UserForm` class:

```php
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('email')
                ->unique(ignoreRecord: true)
                ->required(),

            Select::make('roles')
                ->label(__('Role Name'))
                ->relationship('roles', 'name')
                ->placeholder(__('Superuser')),
        ]);
}
```


## Multi Panel Support

Hexa Lite supports multiple panels, each with its own `auth guard`.

```php
public function panel(Panel $panel): Panel
{
    return $panel->authGuard('reseller');
}
```

```php
public function panel(Panel $panel): Panel
{
    return $panel->authGuard('customer');
}
```

Configure guards in `config/auth.php`.


## Defining Permissions

Define permissions using the `defineGates()` method on Resources, Pages, or Widgets:

```php
use Hexters\HexaLite\HasHexaLite;

class UserResource extends Resource
{
    use HasHexaLite;

    public function defineGates(): array
    {
        return [
            'user.index' => __('Allows viewing the user list'),
            'user.create' => __('Allows creating a new user'),
            'user.update' => __('Allows updating users'),
            'user.delete' => __('Allows deleting users'),
        ];
    }
}
```


## Access Control

Users with no assigned role are treated as **Superusers** and have full access by default.

To restrict access to a resource:

```php
public static function canAccess(): bool
{
    return hexa()->can('user.index');
}
```


### Check Permissions in Code

Useful in queued jobs, commands, or background services:

```php
return hexa()->user(User::first())->can('user.index');
```


### Visible Access

Use `visible()` to conditionally display UI elements:

```php
Actions\CreateAction::make('create')
    ->visible(fn() => hexa()->can(['user.index', 'user.create']));
```


### Laravel Integration

You can still use Laravel’s native authorization:

```php
Auth::user()->can('user.create');

Gate::allows('user.create');

Gate::forUser(User::first())->allows('user.create');

@can('user.create')
    // Blade directive
@endcan
```


## Available Traits

| Trait                    | Description                                   |
| ------------------------ | --------------------------------------------- |
| `HexaLiteRolePermission` | Apply to your `Authenticatable` user model    |
| `HasHexaLite`            | Use in Resources, Pages, Widgets, or Clusters |
| `UuidGenerator`          | Use on models with `uuid` fields              |
| `UlidGenerator`          | Use on models with `ulid` fields              |


## Features in Pro Version

Need more flexibility and control?

Filament Hexa **Pro v3** unlocks powerful features designed for serious projects:

* Role & permission descriptions
* Custom role sorting
* Gate grouping (with nested access)
* Multi-tenancy support
* Meta option storage

A small investment for a much more capable permission system.

Learn more in the official documentation:  
👉 [Hexa Pro Documentation](https://github.com/hexters/hexa-docs)


## License

This project is open-source and licensed under the **MIT License**.
You are free to use, modify, and distribute it with attribution.


## Issues & Feedback

Found a bug or want to contribute?

Open an issue at:
[https://github.com/hexters/hexa-lite/issues](https://github.com/hexters/hexa-lite/issues)

Thank you for using Filament Hexa Lite!
