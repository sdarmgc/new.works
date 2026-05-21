<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Filters;

use Filament\Tables;

class MailGroups extends Filter
{
    public static function make(): Tables\Filters\SelectFilter
    {
        return Tables\Filters\SelectFilter::make('mailGroups')
            ->label(trans('Mail Groups'))
            ->multiple()
            ->searchable()
            ->preload()
            ->relationship('mailGroups', 'name');
    }
}
