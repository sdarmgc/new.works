<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas\Entries;

use Filament\Infolists;

class Active extends Entry
{
    public static function make(): Infolists\Components\TextEntry
    {
        return Infolists\Components\TextEntry::make('profile.active')
            ->formatStateUsing(fn (bool $state): string => $state ? 'Activated' : 'Not Activated')
            ->color('primary')
            ->label(trans('Activated'));
    }
}
