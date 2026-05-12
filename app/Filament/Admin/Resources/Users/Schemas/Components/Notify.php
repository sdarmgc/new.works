<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas\Components;

use Filament\Forms;
use Filament\Forms\Components\Select;

class Notify extends Component
{
    /**
     * @return Select
     */
    public static function make(): Forms\Components\Select
    {
        return Forms\Components\Select::make('notify')
            ->options([
                '0' => 'None',
                '1' => 'Email',
                '2' => 'Etc',
            ])
            ->label(trans('Notify'));
    }
}
