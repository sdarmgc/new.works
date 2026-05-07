<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas\Components;

use Filament\Forms;
use Filament\Forms\Components\TextInput;

class PasswordConfirmation extends Component
{
    /**
     * @return TextInput
     */
    public static function make(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('passwordConfirmation')
            ->hidden(static fn ($record): mixed => $record)
            ->label(trans('password_confirmation'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required(static fn ($record) => ! $record)
            ->dehydrated(false);
    }
}
