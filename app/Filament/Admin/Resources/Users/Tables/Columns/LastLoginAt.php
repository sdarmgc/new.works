<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Columns;

use Filament\Tables;

class LastLoginAt extends Column
{
    public static function make(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('profile.last_login_at')
            ->toggleable(isToggledHiddenByDefault: true)
            ->sortable()
            ->label(trans('Last Login At'))
            ;
    }
}
