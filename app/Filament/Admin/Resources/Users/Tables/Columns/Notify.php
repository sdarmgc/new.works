<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Columns;

use Filament\Tables;

class Notify extends Column
{
    public static function make(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('profile.notify')
            ->formatStateUsing(fn (int $state): string => 
                $state == 0 ? 'None' : ($state == 1 ? 'Email' : ($state == 2 ? 'Other' : ''))
                )
            ->sortable()
            ->toggleable()
            ->label(trans('Notify'))
            ;
    }
}
