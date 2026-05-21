<?php

namespace App\Filament\Admin\Resources\MailGroups\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;

class MailGroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
            ]);
    }
}
