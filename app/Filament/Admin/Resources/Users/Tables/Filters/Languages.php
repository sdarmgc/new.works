<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Filters;

use Filament\Tables;

class Languages extends Filter
{
    public static function make(): Tables\Filters\SelectFilter
    {
        return Tables\Filters\SelectFilter::make('languages')
            ->label(trans('Languages'))
            ->multiple()
            ->searchable()
            ->preload()
            ->relationship('languages', 'name');
    }
}
