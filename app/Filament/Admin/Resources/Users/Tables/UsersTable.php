<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables;

use Filament\Tables\Columns\Column;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;


class UsersTable
{
    protected static array $columns = [];

    public static function configure(Table $table): Table
    {
        return $table
            ->columns(static::getColumns())
            ->filters(UserFilters::class::make(),
                layout: FiltersLayout::AboveContent)
            ->recordActions(UserActions::class::make())
            ->toolbarActions(UserBulkActions::class::make())
            ->persistFiltersInSession()
            ->deferFilters(false)   // enable filter live
            ->paginated([10, 25, 50, 100, 'all']) 
            ;
    }

    public static function getDefaultColumns(): array
    {
        $columns = [
            Columns\ID::make(),
            Columns\Name::make(),
            Columns\Gender::make(),
            Columns\FirstName::make(),
            Columns\LastName::make(),
            Columns\Phone::make(),
            Columns\Email::make(),
            Columns\Roles::make(),
            Columns\Languages::make(),
            Columns\Countries::make(),
            Columns\MailGroups::make(),

            // user profile
            Columns\Responsibility::make(),
            Columns\Extra::make(),
            Columns\Notify::make(),
            // Columns\Active::make(),
            Columns\LastLoginAt::make(),
            Columns\LastLoginIP::make(),

            Columns\Verified::make(),
            Columns\CreatedAt::make(),
            Columns\UpdatedAt::make(),
        ];

        return $columns;
    }

    private static function getColumns(): array
    {
        return array_merge(self::getDefaultColumns(), self::$columns);
    }

    public static function register(Column | array $column): void
    {
        if (is_array($column)) {
            foreach ($column as $item) {
                if (! $item instanceof Column) {
                    continue;
                }

                self::$columns[] = $item;
            }

            return;
        }

        self::$columns[] = $column;
    }
}
