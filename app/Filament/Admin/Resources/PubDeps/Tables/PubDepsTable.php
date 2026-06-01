<?php

namespace App\Filament\Admin\Resources\PubDeps\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;

use \App\Filament\Admin\Resources\Users\Tables\Columns;
use \App\Filament\Admin\Resources\Users\Tables\filters;

class PubDepsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Columns\Gender::make(),
                Columns\FirstName::make(),
                Columns\LastName::make(),
                Columns\Phone::make(),
                Columns\Responsibility::make(),
                // Columns\Notify::make(),
                Columns\LastLoginAt::make(),
                Columns\LastLoginIP::make(),

                Columns\Languages::make(),
                Columns\Countries::make(),
                Columns\MailGroups::make(),
            ])
            ->filters([
                // TrashedFilter::make(),
                
                Filters\Languages::make(),
                Filters\Countries::make(),
                Filters\MailGroups::make(),
                Filters\FullName::make(),
                // Filters\Notify::make(),
            ], layout: FiltersLayout::AboveContent)
            ->recordActions([
                // ViewAction::make(),
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                    // ForceDeleteBulkAction::make(),
                    // RestoreBulkAction::make(),
                ]),
            ])
            ->deferFilters(false)   // enable filter live
            // ->heading('Custom Table Name')
            ->paginated([10, 25, 50, 100, 'all']) 
            ;
    }
}
