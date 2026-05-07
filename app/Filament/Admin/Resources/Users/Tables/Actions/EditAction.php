<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Actions;

use Filament\Actions;

class EditAction extends Action
{
    public static function make(): Actions\Action
    {
        return Actions\EditAction::make()->iconButton()->tooltip(trans('title.edit'));
    }
}
