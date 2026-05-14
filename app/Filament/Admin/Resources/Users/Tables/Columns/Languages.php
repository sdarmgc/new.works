<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Columns;

use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class Languages extends Column
{
    public static function make(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('languages.name')
            ->color('primary')
            ->wrap()
            ->toggleable()
            ->sortable(query: function (Builder $query, string $direction): Builder {
                return $query
                    ->withAggregate('languages', 'name')
                    ->orderBy('languages_name', $direction);
            })
            ->label(trans('Languages'));
    }
}
