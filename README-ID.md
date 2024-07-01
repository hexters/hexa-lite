# Filament Hexa Lite

Filament Hexa merupakan versi sederhana dari [hexters/hexa](https://github.com/hexters/hexa-docs) Filament Hexa is an effortless role & permission plugin for Filament, inspired by the concept of hexters/ladmin. This concept facilitates managing each role and permission inline with code and provides an easy-to-understand interface.

Plugin ini ditujukan hanya untuk Administrator saja, karena memiliki tabel admin yang terpisah dengan tabel user yang sudah disediakan oleh laravel. dan plugin ini akan mereplace file konfig `auth.php` 

![](https://github.com/hexters/assets/blob/main/hexa/v1/edit.png?raw=true)

## About Filament
[FilamentPHP](https://filamentphp.com/) is a lightweight and flexible PHP framework designed for building web applications. It aims to simplify application development by providing a clear structure and high modularity. The framework emphasizes speed, efficiency, and comes with many built-in features that facilitate effective web application development.

## Installasi

> **Note** <br>
Sebelumnya anda perlu menginstall filament package terlebih dahulu, anda bisa melihatnya pada situs resminya di [FilamentPHP](https://filamentphp.com)

``

Anda bisa menginstall nya dengna menjalankan perintah di bawah ini:

```bash
composer require hexters/hexa-lite
```

Setelah itu lakukan instalasi hexa plugin
```bash
php artisan hexa:install
```

Install database migrations
```bash
php artisan migrate
```

Buat superadmin account untuk login admin
```bash
php artisan hexa:account --create
```
## Setup Plugin

Tambahkan plugin Filament `Hexa` pada panel yang sudah dibuat, jika belum lihat caranya di sini [Creating a new panel](https://filamentphp.com/docs/3.x/panels/configuration#creating-a-new-panel)

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

## Mendeklarasikan Izin Akses 

### Resource, Page, & Cluster

Cara mendeklarasikan permissions akses kepada Resource, dan Page, untuk Culuster anda perlu mengupgrade ke package [hexters/hexa](https://github.com/hexters/hexa-docs)

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

Cara mendeklarasikan permission akses kepada Widget, untuk widget ada perbedaan pada method access nya dia menggunakan method `canView()`

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

Untuk widget bisa di dapat pada [hexters/hexa](https://github.com/hexters/hexa-docs)

## Additional Access
Selain cara diatas anda bisa menambahkan tambahan permission untuk keperluan lainnya diluar Page, Resource, Widget, dan Cluster. Anda dapat menambahkannya pada file `/config/other-permissions.php` :

```php
use Hexters\HexaLite\Helpers\Can;

return [
    Can::make(id: 'access.horizon.page')
        ->name(name: 'View Horizon Page')
        ->description(description: 'Admain is allowed to view the Horizon page.'),
];
```
Untuk Additional Access bisa di dapat pada [hexters/hexa](https://github.com/hexters/hexa-docs)

### Action dll.

Anda bisa mengunakan method `visible()` pada beberapa `Class Component` sebagai contok kita akan coba pada sebuah button.

```php
Tables\Actions\EditAction::make()
    ->visible(hexa()->can('access.user.edit')),
```

dan untuk pemberian akses pada class yang ber extended kepada `Filament\Resources\Pages\EditRecord`, `Filament\Resources\Pages\CreateRecord`, `Filament\Resources\Pages\ListRecords`, `Filament\Resources\Pages\ViewRecords` kita bisa menggunakan
```php
/**
 * @param  array<string, mixed>  $parameters
 */
public static function canAccess(array $parameters = []): bool
{
    return hexa()->can('access.user.edit');
}
```

## Memeriksa Izin Akses

Akses ini dapat diberikan kepada Resource, Page, Widget, Button Action, dll. pemberian akses dapat dilakukan dengan cara seperti dibawah ini.


Dengan menggunakan hexa utility function
```php
hexa()->can('hexa.admin')
```

Dengan menggunakan laravel auth can function
```php
auth()->user()?->can('hexa.admin')
```

Dengan menggunalan laravel Gate class
```php
use use Illuminate\Support\Facades\Gate;

. . .

Gate::allows('hexa.admin')
```

Pada blade anda bisa menggunakan contoh seperti di bawah ini.

```html
<div>
    @can('hexa.admin')
        // Content here ...
    @endcan
</div>
```

## Options Setting

Plugin ini dilengkapi dengan system cache yang mudah untuk digunakan, yang berfungsi untuk menyimpan beberapa setingan-setingan yang diperlukan oleh aplikasi. lihat file `app/Filament/Pages/Option.php`. Anda dapat menggunakan Form component untuk membuat jenis jenis form input.

```php
use Filament\Forms\Components\TextInput;

public function formOptions(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('referral-commision')
                ->default(hexa()->getOption('referral-commision', 10)) #<-- required fot set the value
                ->required()
                ->suffix('%')
                ->numeric(),
        ]);
}
```

Untuk memanggilnya anda bisa menggunakan utility function yang sudah disiapkan oleh Hexa

```php
hexa()->getOption('referral-commision', 10)
```

Jika anda ingin menyimpananya secara manual anda dapat menggunakan utility function di bawah

```php
hexa()->setOption('key-option', 'The option value can be a string, array, number, etc.')
```

Untuk Options Setting bisa di dapat pada [hexters/hexa](https://github.com/hexters/hexa-docs)

## License
This project is licensed under the MIT License - see the [LICENSE](https://github.com/hexters/hexa-lite/blob/main/LICENSE.md) file for details.


## Issue

Jika anda medapatkan issue mengenai plugin ini, anda bisa melakukan submit issue pada repository ini
[Filament Hexa Issue](https://github.com/hexters/hexa-lite/issues)

Terimakasih anda telah menggunakan plugin ini semoga plugin ini dapat mempercepat prosese dalam membuat aplikasi yang powerfull.

Happy Coding 🧑‍💻 🧑‍💻 🧑‍💻
