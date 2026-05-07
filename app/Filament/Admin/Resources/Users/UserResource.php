<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationLabel(): string
    {
        return trans('User');
    }

    public static function getModel(): string
    {
        return 'App\\Models\\User';
    }

    public static function getNavigationSort(): ?int
    {
        return config('filament-users.navigation_sort') ?? 9;
    }

    public static function getNavigationIcon(): string
    {
        return config('filament-users.navigation_icon') ?? Heroicon::OutlinedUser;
    }

    public static function getPluralLabel(): string
    {
        return trans('Users');
    }

    public static function getLabel(): string
    {
        return trans('User');
    }

    public static function getNavigationGroup(): ?string
    {
        if (config('filament-users.shield')) {
            return __('filament-shield::filament-shield.nav.group');
        }

        return config('filament-users.group') ?? trans('filament-users::user.group');
    }

    public function getTitle(): string
    {
        return trans('title.resource');
    }

    public static function form(Schema $schema): Schema
    {
        return config('filament-users.resource.form.class')::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return config('filament-users.resource.infolist.class')::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return config('filament-users.resource.table.class')::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return
            config('filament-users.simple')
                ? [
                    'index' => Pages\ManageUsers::route('/'),
                ] : [
                    'index' => Pages\ListUsers::route('/'),
                    'create' => Pages\CreateUser::route('/create'),
                    'edit' => Pages\EditUser::route('/{record}/edit'),
                    'view' => Pages\ViewUser::route('/{record}'),
                ];
    }
}
