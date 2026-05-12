<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Filters;

use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class Notify extends Filter
{
    public static function make(): Tables\Filters\SelectFilter
    {
        return Tables\Filters\SelectFilter::make('notify')
            ->label(trans('Notify'))
            ->options([
                '0' => 'None',
                '1' => 'Email',
                '2' => 'Etc',
            ])
            ->query(function (Builder $query, array $data): Builder {
                return $query->when(
                    $data['value'],
                    fn (Builder $query, $value): Builder => $query->whereHas('profile', function (Builder $query) use ($value) {
                        $query->where('notify', $value);
                    }),
                );
            })
            ;
    }
}
