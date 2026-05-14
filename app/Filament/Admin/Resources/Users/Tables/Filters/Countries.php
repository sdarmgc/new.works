<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Filters;

use Filament\Tables;

class Countries extends Filter
{
    public static function make(): Tables\Filters\SelectFilter
    {
        return Tables\Filters\SelectFilter::make('countries')
            ->label(trans('Country'))
            ->multiple()
            ->searchable()
            ->preload()
            ->relationship('countries', 'name');
    }
}
