<?php

return [

    'models' => [
        'role' => \Hexters\HexaLite\Models\HexaRole::class,
        'user' => \App\Models\User::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Navigation Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the navigation label and group for the Role & Permissions menu item.
    | You can use translation keys (e.g., 'navigation.groups.settings') or plain strings.
    | Use closures for dynamic translation: fn() => __('navigation.groups.settings')
    |
    */

    'navigation' => [
        'label' => 'Role & Permissions',
        'group' => 'Settings',
    ],

];
