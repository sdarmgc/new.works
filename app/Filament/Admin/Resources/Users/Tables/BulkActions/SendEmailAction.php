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
            ->action(function (Collection $records, \Livewire\Component $livewire) {
                $addresses = $records->pluck('email')->implode(', ');
                return $livewire->redirect(
                    route('email.compose', ['to' => $addresses])
                );
            })
            ;
    }
}
