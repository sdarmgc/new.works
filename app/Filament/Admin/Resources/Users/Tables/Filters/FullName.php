<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Filters;

use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;

class FullName extends Filter
{
    public static function make(): Tables\Filters\Filter
    {
        return Tables\Filters\Filter::make('fullname')
            ->form([
                TextInput::make('fullname')->label('Full Name'),
            ])
            ->query(function (Builder $query, array $data): Builder {
                return $query->when(
                    $data['fullname'],
                    fn (Builder $query, $value): Builder => $query->whereHas('profile', function (Builder $query) use ($value) {
                        $query->where('first_name', 'like', "{$value}%")
                            ->orWhere('last_name', 'like', "{$value}%")
                        ;
                    }),
                );
            })
            ;
    }
}
