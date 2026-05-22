<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\BulkActions;

use Filament\Actions;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;

class SendEmailAction extends Action
{
    public static function make(): Actions\BulkAction
    {
        return Actions\BulkAction::make('send_email')
            ->icon('heroicon-o-envelope')
            ->color('primary')
            ->label(trans('Send Email'))
            ->openUrlInNewTab() // Optional: opens in a new tab
            ->url(function (Collection $records) {
                $addresses = $records->pluck('email')->implode(',');
                return route('email.compose', ['to' => $addresses]);
            })
            // ->deselectRecordsAfterCompletion()
            ;
    }
}
