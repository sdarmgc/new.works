<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Columns;

use Filament\Tables;

class Gender extends Column
{
    public static function make(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('profile.gender')
            ->formatStateUsing(fn (bool $state): string => $state ? 'Br.' : 'Sis.')
            ->sortable()
            ->toggleable()
            ->label(trans('Title'))
            ;
    }
}
