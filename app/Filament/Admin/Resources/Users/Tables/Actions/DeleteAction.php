<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Actions;

use Filament\Actions;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class DeleteAction extends Action
{
    public static function make(): Actions\Action
    {
        return Actions\DeleteAction::make()
            ->using(static function (Model $record, Actions\Action $action) {
                self::checkIfLastUserOrCurrentUser($record, $action);
            })
            ->iconButton()
            ->tooltip(trans('title.delete'));
    }

    private static function checkIfLastUserOrCurrentUser(Model $record, Actions\Action $action): void
    {
        $count = User::query()->count();
        if ($count === 1) {
            Notification::make()
                ->title(trans('notificaitons.last.title'))
                ->body(trans('notificaitons.last.body'))
                ->danger()
                ->icon('heroicon-o-exclamation-triangle')
                ->send();

            return;
        }

        if (auth()->user()->id === $record->id) {
            Notification::make()
                ->title(trans('notificaitons.self.title'))
                ->body(trans('notificaitons.self.body'))
                ->danger()
                ->icon('heroicon-o-exclamation-triangle')
                ->send();

            return;
        }

        $record->delete();
        $action->success();
    }
}
