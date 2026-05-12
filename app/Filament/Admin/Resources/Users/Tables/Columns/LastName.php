<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Columns;

use Filament\Tables;

class LastName extends Column
{
    public static function make(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('profile.last_name')
            ->sortable()
            ->toggleable()
            ->searchable()
            ->label(trans('Last Name'))
            ;
    }
}
