<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas\Entries;

use Filament\Infolists;

class Countries extends Entry
{
    public static function make(): Infolists\Components\TextEntry
    {
        return Infolists\Components\TextEntry::make('countries.name')
            ->visible(static fn ($record) => $record->countries->isNotEmpty())
            ->columnSpanFull()
            ->color('primary')
            ->label(trans('Country'));
    }
}
