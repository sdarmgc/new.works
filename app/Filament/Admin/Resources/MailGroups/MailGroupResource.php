<?php

namespace App\Filament\Admin\Resources\MailGroups;

use App\Filament\Admin\Resources\MailGroups\Pages\CreateMailGroup;
use App\Filament\Admin\Resources\MailGroups\Pages\EditMailGroup;
use App\Filament\Admin\Resources\MailGroups\Pages\ListMailGroups;
use App\Filament\Admin\Resources\MailGroups\Pages\ManageMailGroups;
use App\Filament\Admin\Resources\MailGroups\Schemas\MailGroupForm;
use App\Filament\Admin\Resources\MailGroups\Tables\MailGroupsTable;
use App\Models\MailGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MailGroupResource extends Resource
{
    protected static ?string $model = MailGroup::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationGroup(): ?string
    {
        return trans('Manage Resources');
    }

    public static function form(Schema $schema): Schema
    {
        return MailGroupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MailGroupsTable::configure($table);
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
                    'index' => ManageMailGroups::route('/'),
                ] : [
                    'index' => ListMailGroups::route('/'),
                    'create' => CreateMailGroup::route('/create'),
                    'edit' => EditMailGroup::route('/{record}/edit'),
                ];
    }
}
