<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Columns;

use Filament\Tables;

class Verified extends Column
{
    public static function make(): Tables\Columns\IconColumn
    {
        return Tables\Columns\IconColumn::make('email_verified_at')
            ->state(static fn ($record) => (bool) $record->email_verified_at)
            ->boolean()
            ->sortable()
            ->label(trans('email_verified_at'))
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
