<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas\Components;

use Filament\Forms;
use Filament\Forms\Components\Select;

class Gender extends Component
{
    /**
     * @return Select
     */
    public static function make(): Forms\Components\Select
    {
        return Forms\Components\Select::make('gender')
            ->options([
                '0' => 'None',
                '1' => 'Br.',
                '2' => 'Sis.',
            ])
            ->label(trans('Title'));
    }
}
