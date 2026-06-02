<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Columns;

use Filament\Tables;

class Name extends Column
{
    public static function make(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('name')
            ->sortable()
            ->searchable()
            ->toggleable(isToggledHiddenByDefault: true)
            ->label(trans('Nickname'))
            ;
    }
}
