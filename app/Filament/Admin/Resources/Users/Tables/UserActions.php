<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables;

use Filament\Actions;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;

class UserActions
{
    /**
     * @var array
     */
    protected static $actions = [];

    public static function make(): array
    {
        return self::getActions();
    }

    private static function getDefaultActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\EditAction::make(),
            // Actions\ChangePassword::make(),
            Actions\DeleteAction::make()
                ->visible(fn ($record): bool => !$record->trashed()),
            ForceDeleteAction::make()
                ->visible(fn ($record): bool => $record->trashed()),
            RestoreAction::make()
                ->visible(fn ($record): bool => $record->trashed()),
        ];
    }

    private static function getActions(): array
    {
        return array_merge(self::getDefaultActions(), self::$actions);
    }

    public static function register(\Filament\Actions\Action | array $action): void
    {
        if (is_array($action)) {
            foreach ($action as $item) {
                if (! $item instanceof \Filament\Actions\Action) {
                    continue;
                }

                self::$actions[] = $item;
            }

            return;
        }

        self::$actions[] = $action;
    }
}
