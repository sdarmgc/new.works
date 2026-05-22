<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas\Entries;

use Filament\Infolists;

class Responsibility extends Entry
{
    public static function make(): Infolists\Components\TextEntry
    {
        return Infolists\Components\TextEntry::make('profile.responsibility')
            // ->columnSpanFull()
            ->label(trans('Responsibility'));
    }
}
