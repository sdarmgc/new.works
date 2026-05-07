<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Filters;

use Filament\Tables;

class Teams extends Filter
{
    public static function make(): Tables\Filters\SelectFilter
    {
        return Tables\Filters\SelectFilter::make('teams')
            ->label(trans('teams'))
            ->multiple()
            ->searchable()
            ->preload()
            ->relationship('teams', 'name');
    }
}
