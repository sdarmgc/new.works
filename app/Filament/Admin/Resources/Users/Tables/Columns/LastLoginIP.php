<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Columns;

use Filament\Tables;

class LastLoginIP extends Column
{
    public static function make(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('profile.last_login_ip')
            ->toggleable(isToggledHiddenByDefault: true)
            ->label(trans('Last Login IP'))
            ;
    }
}
