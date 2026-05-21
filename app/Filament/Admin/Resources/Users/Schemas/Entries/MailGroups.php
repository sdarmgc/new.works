<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas\Entries;

use Filament\Infolists;

class MailGroups extends Entry
{
    public static function make(): Infolists\Components\TextEntry
    {
        return Infolists\Components\TextEntry::make('mailGroups.name')
            ->visible(static fn ($record) => $record->mailGroups->isNotEmpty())
            ->columnSpanFull()
            ->color('primary')
            ->label(trans('Mail Groups'));
    }
}
