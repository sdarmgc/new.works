<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Columns;

use Filament\Tables;

class Extra extends Column
{
    public static function make(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('profile.extra')
            ->toggleable(isToggledHiddenByDefault: true)
            ->searchable()
            ->label(trans('Extra'))
            ;
    }
}
