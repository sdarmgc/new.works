<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas\Entries;

use Filament\Infolists;

class PhoneApp extends Entry
{
    public static function make(): Infolists\Components\TextEntry
    {
        return Infolists\Components\TextEntry::make('profile.phone_app')
            // ->columnSpanFull()
            ->label(trans('Phone App'));
    }
}
