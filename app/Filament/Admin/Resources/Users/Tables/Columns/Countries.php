<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Columns;

use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;


class Countries extends Column
{
    public static function make(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('countries.name')
            ->formatStateUsing(static fn ($state) => str($state)->replace('_', ' ')->replace('-', ' ')->title())
            ->color('primary')
            ->toggleable()
            ->sortable(query: function (Builder $query, string $direction): Builder {
                return $query
                    ->withAggregate('countries', 'name')
                    ->orderBy('countries_name', $direction);
            })
            ->label(trans('Country'));
    }
}
