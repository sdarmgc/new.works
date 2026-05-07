<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Columns;

use Filament\Tables;

class Languages extends Column
{
    public static function make(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('languages.name')
            ->formatStateUsing(static fn ($state) => str($state)->replace('_', ' ')->replace('-', ' ')->title())
            ->icon('heroicon-o-shield-check')
            ->color('success')
            ->toggleable()
            ->badge()
            ->label(trans('roles'));
    }
}
