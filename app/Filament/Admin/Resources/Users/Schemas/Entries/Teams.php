<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas\Entries;

use Filament\Infolists;

class Teams extends Entry
{
    public static function make(): Infolists\Components\TextEntry
    {
        return Infolists\Components\TextEntry::make('teams.name')
            ->visible(static fn ($record) => $record->teams->isNotEmpty())
            ->columnSpanFull()
            ->badge()
            ->color('info')
            ->icon('heroicon-o-users')
            ->label(trans('teams'));
    }
}
