<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\BulkActions;

use Filament\Actions;
use Filament\Forms;
use Illuminate\Database\Eloquent\Collection;

class TeamsAction extends Action
{
    public static function make(): Actions\BulkAction
    {
        return Actions\BulkAction::make('teams')
            ->requiresConfirmation()
            ->color('info')
            ->icon('heroicon-o-users')
            ->label(trans('teams'))
            ->schema([
                Forms\Components\Select::make('teams')
                    ->label(trans('teams'))
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->options(config('filament-users.team_model')::query()->pluck('name', 'id')->toArray()),
            ])
            ->action(static function (array $data, Collection $records, Actions\BulkAction $action) {
                $teams = $data['teams'];

                $records->each(static function ($user) use ($teams) {
                    $user->teams()->sync($teams);
                });

                $action->success();
            })
            ->deselectRecordsAfterCompletion();
    }
}
