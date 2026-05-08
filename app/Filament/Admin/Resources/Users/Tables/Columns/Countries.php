<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Columns;

use Filament\Tables;

class Countries extends Column
{
    public static function make(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('countries.name')
            ->formatStateUsing(static fn ($state) => str($state)->replace('_', ' ')->replace('-', ' ')->title())
            ->color('primary')
            ->toggleable()
            ->label(trans('Countries'));
    }
}
