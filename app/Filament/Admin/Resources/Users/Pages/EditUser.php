<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Admin\Resources\Users\UserResource;
use App\Models\User;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()->using(static function ($record, Action $action) {
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

                return redirect()->to(UserResource::getUrl('index'));
            }),
        ];
    }
}
