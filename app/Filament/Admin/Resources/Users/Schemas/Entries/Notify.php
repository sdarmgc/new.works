<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas\Entries;

use Filament\Infolists;

class Notify extends Entry
{
    public static function make(): Infolists\Components\TextEntry
    {
        return Infolists\Components\TextEntry::make('profile.notify')
            ->formatStateUsing(fn (int $state): string => 
                $state == 0 ? 'None' : ($state == 1 ? 'Email' : ($state == 2 ? 'Other' : ''))
                )
            ->color('primary')
            ->label(trans('Notify'));
    }
}
