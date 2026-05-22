<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Filters;

use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;

class Responsibility extends Filter
{
    public static function make(): Tables\Filters\Filter
    {
        return Tables\Filters\Filter::make('responsibility')
            ->form([
                TextInput::make('responsibility')->label('Responsibility'),
            ])
            ->query(function (Builder $query, array $data): Builder {
                return $query->when(
                    $data['responsibility'],
                    fn (Builder $query, $value): Builder => $query->whereHas('profile', function (Builder $query) use ($value) {
                        $query->where('responsibility', 'like', "{$value}%")
                        ;
                    }),
                );
            })
            ;
    }
}
