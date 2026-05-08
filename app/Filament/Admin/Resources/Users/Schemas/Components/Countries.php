<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas\Components;

use Filament\Forms;
use Filament\Forms\Components\Select;

class Countries extends Component
{
    /**
     * @return Select
     */
    public static function make(): Forms\Components\Select
    {
        return Forms\Components\Select::make('countries')
            ->columnSpanFull()
            ->multiple()
            ->preload()
            ->relationship('countries', 'name')
            ->label(trans('Countries'));
    }
}
