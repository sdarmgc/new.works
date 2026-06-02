<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Columns;

use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class Roles extends Column
{
    public static function make(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('roles.name')
            ->formatStateUsing(static fn ($state) => str($state)->replace('_', ' ')->replace('-', ' ')->title())
            ->icon('heroicon-o-shield-check')
            ->color('success')
            ->toggleable()
            ->badge()
            ->label(trans('labels.backend.access.users.table.roles'))
            ->sortable(query: function (Builder $query, string $direction): Builder {
                return $query
                    ->withAggregate('roles', 'name')
                    ->orderBy('name', $direction);
            })
            ;
    }
}
