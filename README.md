# Filament Hexa Lite

Filament Hexa is a simplified version of [hexters/hexa](https://github.com/hexters/hexa-docs). Filament Hexa is an effortless role & permission plugin for Filament, inspired by the concept of hexters/ladmin. This concept facilitates managing each role and permission inline with code and provides an easy-to-understand interface.

This plugin is intended only for Administrators, as it has a separate admin table from the user table provided by Laravel. Additionally, this plugin will replace the `auth.php` configuration file.

![](https://github.com/hexters/assets/blob/main/hexa/v1/edit.png?raw=true)

## About Filament
[FilamentPHP](https://filamentphp.com/) is a lightweight and flexible PHP framework designed for building web applications. It aims to simplify application development by providing a clear structure and high modularity. The framework emphasizes speed, efficiency, and comes with many built-in features that facilitate effective web application development.

## Installation

> **Note** <br>
You need to install the filament package first. You can refer to the official site at [FilamentPHP](https://filamentphp.com)

You can install it by running the command below:

```bash
composer require hexters/hexa-lite
```

Then, proceed with the installation of the hexa plugin:
```bash
php artisan hexa:install
```

Install database migrations:
```bash
php artisan migrate
```

Create a superadmin account for admin login:
```bash
php artisan hexa:account --create
```

## Plugin Setup

Add the Filament `Hexa` plugin to the created panel. If you haven't created one yet, see how to do it here [Creating a new panel](https://filamentphp.com/docs/3.x/panels/configuration#creating-a-new-panel).

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

## Declaring Access Permissions

### Resource, Page, & Cluster

To declare access permissions for Resources and Pages, for Clusters you need to upgrade to the [hexters/hexa](https://github.com/hexters/hexa-docs) package.

```php
use Hexters\HexaLite\Traits\HexAccess;

. . .

use HexAccess;

protected static ?string $permissionId = 'access.user';

protected static ?string $descriptionPermission = 'Admin can manage User accounts';

/**
 * Additional permission (optional)
 * You can add it or not depending on the needs of your application.
 */
protected static ?array $subPermissions = [
    'access.user.create' => 'Can Create',
    'access.user.edit' => 'Can Edit',
    'access.user.delete' => 'Can Delete',
];

public static function canAccess(): bool
{
    return hexa()->can(static::$permissionId);
}

. . .
```

### Widget

To declare access permissions for Widgets, there is a difference in the access method as it uses the `canView()` method.

```php
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Hexters\HexaLite\Traits\HexAccess;

class StatsOverview extends BaseWidget
{
    use HexAccess;

    protected static ?string $permissionId = 'widget.overview';

    protected static ?string $descriptionPermission = 'Admin can view report overview';

    public static function canView(): bool
    {
        return hexa()->can(static::$permissionId);
    }

    . . .
}
```

For widgets, you can refer to [hexters/hexa](https://github.com/hexters/hexa-docs).

## Additional Access
Apart from the methods above, you can add additional permissions for other needs outside of Page, Resource, Widget, and Cluster. You can add them in the file `/config/other-permissions.php`:

```php
use Hexters\HexaLite\Helpers\Can;

return [
    Can::make(id: 'access.horizon.page')
        ->name(name: 'View Horizon Page')
        ->description(description: 'Admin is allowed to view the Horizon page.'),
];
```

For Additional Access, refer to [hexters/hexa](https://github.com/hexters/hexa-docs).

### Actions, etc.

You can use the `visible()` method on several `Class Components`. For example, let's try it on a button.

```php
Tables\Actions\EditAction::make()
    ->visible(hexa()->can('access.user.edit')),
```

For giving access to classes extended to `Filament\Resources\Pages\EditRecord`, `Filament\Resources\Pages\CreateRecord`, `Filament\Resources\Pages\ListRecords`, `Filament\Resources\Pages\ViewRecords`, you can use:
```php
/**
 * @param  array<string, mixed>  $parameters
 */
public static function canAccess(array $parameters = []): bool
{
    return hexa()->can('access.user.edit');
}
```

## Checking Access Permissions

Access can be granted to Resources, Pages, Widgets, Button Actions, etc. The access can be given as shown below.

Using the hexa utility function:
```php
hexa()->can('hexa.admin')
```

Using Laravel's auth can function:
```php
auth()->user()?->can('hexa.admin')
```

Using Laravel's Gate class:
```php
use Illuminate\Support\Facades\Gate;

. . .

Gate::allows('hexa.admin')
```

In a blade template, you can use it as shown below.

```html
<div>
    @can('hexa.admin')
        // Content here ...
    @endcan
</div>
```

## Options Setting

This plugin comes with an easy-to-use cache system that stores various settings required by the application. Check the file `app/Filament/Pages/Option.php`. You can use the Form component to create various types of form inputs.

```php
use Filament\Forms\Components\TextInput;

public function formOptions(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('referral-commision')
                ->default(hexa()->getOption('referral-commision', 10)) #<-- required to set the value
                ->required()
                ->suffix('%')
                ->numeric(),
        ]);
}
```

To call it, you can use the utility function provided by Hexa:

```php
hexa()->getOption('referral-commision', 10)
```

If you want to save it manually, you can use the utility function below:

```php
hexa()->setOption('key-option', 'The option value can be a string, array, number, etc.')
```

For Options Setting, refer to [hexters/hexa](https://github.com/hexters/hexa-docs).

## License
This project is licensed under the MIT License - see the [LICENSE](https://github.com/hexters/hexa-lite/blob/main/LICENSE.md) file for details.

## Issue

If you encounter any issues with this plugin, you can submit them to the repository:
[Filament Hexa Issue](https://github.com/hexters/hexa-lite/issues)

Thank you for using this plugin. We hope it speeds up your process in creating powerful applications.

Happy Coding 🧑‍💻 🧑‍💻 🧑‍💻