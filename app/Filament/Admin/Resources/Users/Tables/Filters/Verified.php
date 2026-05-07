<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Filters;

use Filament\Forms;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class Verified extends Filter
{
    public static function make(): Tables\Filters\Filter
    {
        return Tables\Filters\Filter::make('verified')
            ->schema([
                Forms\Components\Toggle::make('verified')->label(trans('verified')),
            ])
            ->label(trans('verified'))
            ->query(static fn (Builder $query, array $data): Builder => $query->when($data['verified'], static fn (
                Builder $q,
                $verified,
            ) => $verified ? $q->whereNotNull('email_verified_at') : $q->whereNull('email_verified_at')));
    }
}
