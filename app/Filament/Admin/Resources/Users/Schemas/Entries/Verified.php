<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas\Entries;

use Filament\Infolists;

class Verified extends Entry
{
    public static function make(): Infolists\Components\TextEntry
    {
        return Infolists\Components\TextEntry::make('email_verified_at')
            ->visible(static fn ($record) => $record->email_verified_at)
            ->label(trans('email_verified_at'));
    }
}
