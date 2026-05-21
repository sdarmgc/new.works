<?php

namespace App\Filament\Admin\Resources\PubDeps;

use App\Filament\Admin\Resources\PubDeps\Pages\CreatePubDep;
use App\Filament\Admin\Resources\PubDeps\Pages\EditPubDep;
use App\Filament\Admin\Resources\PubDeps\Pages\ListPubDeps;
use App\Filament\Admin\Resources\PubDeps\Pages\ViewPubDep;
use App\Filament\Admin\Resources\PubDeps\Schemas\PubDepForm;
use App\Filament\Admin\Resources\PubDeps\Schemas\PubDepInfolist;
use App\Filament\Admin\Resources\PubDeps\Tables\PubDepsTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PubDepResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Pub. Department';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Pub. Department';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('profile', fn ($query) => $query->where('active', true));
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('Manage User');
    }

    // public function getTitle(): string
    // {
    //     return trans('Publication Department');
    // }

    public static function form(Schema $schema): Schema
    {
        // return PubDepForm::configure($schema);
        return \App\Filament\Admin\Resources\Users\Schemas\UserForm::class::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PubDepInfolist::class::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PubDepsTable::class::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        $isSimple = true;
        return
            $isSimple
                ? [
                    'index' => \App\Filament\Admin\Resources\Users\Pages\ManageUsers::route('/'),
                ] : [
                    'index' => ListPubDeps::route('/'),
                    'create' => \App\Filament\Admin\Resources\Users\Pages\CreateUser::route('/create'),
                    'edit' => \App\Filament\Admin\Resources\Users\Pages\EditUser::route('/{record}/edit'),
                    'view' => \App\Filament\Admin\Resources\Users\Pages\ViewUser::route('/{record}'),
                ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
