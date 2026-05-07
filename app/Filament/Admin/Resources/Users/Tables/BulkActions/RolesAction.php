<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\BulkActions;

use Filament\Actions;
use Filament\Forms;
use Illuminate\Database\Eloquent\Collection;

class RolesAction extends Action
{
    public static function make(): Actions\BulkAction
    {
        return Actions\BulkAction::make('roles')
            ->icon('heroicon-o-shield-check')
            ->color('success')
            ->requiresConfirmation()
            ->label(trans('roles'))
            ->schema([
                Forms\Components\Select::make('roles')
                    ->label(trans('roles'))
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->options(config('filament-users.roles_model')::query()->pluck('name', 'id')->toArray()),
            ])
            ->action(static function (array $data, Collection $records, Actions\BulkAction $action) {
                $roles = $data['roles'];

                $records->each(static function ($user) use ($roles) {
                    $user->roles()->sync($roles);
                });

                $action->success();
            })
            ->deselectRecordsAfterCompletion();
    }
}
