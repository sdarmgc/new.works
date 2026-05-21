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
        return 9;
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-user' ?? Heroicon::OutlinedUser;
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
        return trans('Manage User');
    }

    public function getTitle(): string
    {
        return trans('title.resource');
    }

    public static function form(Schema $schema): Schema
    {
        return Schemas\UserForm::class::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return Schemas\UserInfolist::class::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return Tables\UsersTable::class::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\UserProfilesRelationManager::class,
            // RelationManagers\LanguagesRelationManager::class,
            // RelationManagers\CountriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        $isSimple = true;
        return
            $isSimple
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
