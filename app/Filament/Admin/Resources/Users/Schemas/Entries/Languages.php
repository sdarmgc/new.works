<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas\Entries;

use Filament\Infolists;

class Languages extends Entry
{
    public static function make(): Infolists\Components\TextEntry
    {
        return Infolists\Components\TextEntry::make('languages.name')
            ->visible(static fn ($record) => $record->languages->isNotEmpty())
            ->columnSpanFull()
            ->color('primary')
            ->label(trans('Languages'));
    }
}
