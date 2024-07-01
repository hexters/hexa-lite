<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Hexters\HexaLite\Pages\Option as BaseOptionPage;

class Option extends BaseOptionPage
{
    public function formOptions(Form $form): Form
    {
        return $form
            ->schema([
                // TextInput::make('referral-commision')
                //     ->default(hexa()->getOption('referral-commision', 10))
                //     ->required()
                //     ->suffix('%')
                //     ->numeric(),
            ]);
    }
}
