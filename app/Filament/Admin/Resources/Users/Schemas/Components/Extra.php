<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas\Components;

use Filament\Forms;
use Filament\Forms\Components\Textarea;

class Extra extends Component
{
    /**
     * @return Textarea
     */
    public static function make(): Forms\Components\Textarea
    {
        return Forms\Components\Textarea::make('extra')
            ->columnSpanFull()
            ->label(trans('Extra'));
    }
}
