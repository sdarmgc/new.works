<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Columns;

use Filament\Tables;

class UpdatedAt extends Column
{
    public static function make(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('updated_at')
            ->dateTime()
            ->description(static fn ($record) => $record->updated_at->diffForHumans())
            ->toggleable(isToggledHiddenByDefault: true)
            ->sortable()
            ->label(trans('Updated At'))
            ;
    }
}
