<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas\Components;

use Filament\Forms;
use Filament\Forms\Components\TextInput;

class Email extends Component
{
    /**
     * @return TextInput
     */
    public static function make(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('email')
            ->email()
            ->required()
            ->label(trans('email'));
    }
}
