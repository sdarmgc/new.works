<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Columns;

use Filament\Tables;

class PhoneApp extends Column
{
    public static function make(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('profile.phone_app')
            ->toggleable(isToggledHiddenByDefault: true)
            ->label(trans('PhoneApp'))
            ;
    }
}
