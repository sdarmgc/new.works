<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas\Components;

use Filament\Forms;
use Filament\Forms\Components\Select;

class MailGroups extends Component
{
    /**
     * @return Select
     */
    public static function make(): Forms\Components\Select
    {
        return Forms\Components\Select::make('mailGroups')
            ->columnSpanFull()
            ->multiple()
            ->preload()
            ->relationship('mailGroups', 'name')
            ->label(trans('Mail Groups'));
    }
}
