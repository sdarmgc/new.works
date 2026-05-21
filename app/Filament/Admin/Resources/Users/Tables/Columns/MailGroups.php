<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Columns;

use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class MailGroups extends Column
{
    public static function make(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('mailGroups.name')
            ->color('primary')
            ->wrap()
            ->toggleable()
            ->sortable(query: function (Builder $query, string $direction): Builder {
                return $query
                    ->withAggregate('mailGroups', 'name')
                    ->orderBy('mail_groups_name', $direction);
            })
            ->label(trans('Mail Groups'));
    }
}
