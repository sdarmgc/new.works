<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Filters;

use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class Gender extends Filter
{
    public static function make(): Tables\Filters\SelectFilter
    {
        return Tables\Filters\SelectFilter::make('gender')
            ->label(trans('Title'))
            ->options([
                '0' => 'None',
                '1' => 'Br.',
                '2' => 'Sis.',
            ])
            ->query(function (Builder $query, array $data): Builder {
                return $query->when(
                    $data['value'],
                    fn (Builder $query, $value): Builder => $query->whereHas('profile', function (Builder $query) use ($value) {
                        $query->where('gender', $value);
                    }),
                );
            })
            ;
    }
}
