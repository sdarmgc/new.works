<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Columns;

use Filament\Tables;

class Active extends Column
{
    public static function make(): Tables\Columns\IconColumn
    {
        return Tables\Columns\IconColumn::make('profile.active')
            ->sortable()
            ->label(trans('Activated'));
    }
}
