<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas\Entries;

use Filament\Infolists;

class Gender extends Entry
{
    public static function make(): Infolists\Components\TextEntry
    {
        return Infolists\Components\TextEntry::make('profile.gender')
            ->formatStateUsing(fn (int $state): string => $state == 1 ? 'Bro.' : ($state == 2 ? 'Sis.' : ''))
            ->columnSpanFull()
            ->label(trans('Title'));
    }
}
