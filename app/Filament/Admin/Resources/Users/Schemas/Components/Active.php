<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas\Components;

use Filament\Forms;
use Filament\Forms\Components\Toggle;

class Active extends Component
{
    /**
     * @return Toggle
     */
    public static function make(): Forms\Components\Toggle
    {
        return Forms\Components\Toggle::make('active')
            ->label(trans('Activated'));
    }
}
