<?php

namespace App\Filament\Admin\Resources\Users\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;

class UserProfilesRelationManager extends RelationManager
{
    protected static string $relationship = 'profile';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('gender')->required()->options([
                        '0' => 'Sister',
                        '1' => 'Brother'
                    ])->label(trans('Title')),
                TextInput::make('first_name')->required()->maxLength(255)->label(trans('First Name')),
                TextInput::make('last_name')->required()->maxLength(255)->label(trans('Last Name')),
                TextInput::make('phone')->maxLength(255)->label(trans('Phone')),
                TextInput::make('phone_app')->maxLength(255)->label(trans('phone_app')),
                TextArea::make('extra')->maxLength(1023)->label(trans('extra')),
                Select::make('notify')->required()->options([
                        '0' => 'None',
                        '1' => 'Email',
                        '2' => 'Other'
                    ])->label(trans('Notify')),
                Toggle::make('active')->label(trans('Activated')),
                TextInput::make('last_login_at')->readOnly()->label(trans('Last Login At')),
                TextInput::make('last_login_ip')->readOnly()->label(trans('Last Login IP')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('first_name')
            ->columns([
                TextColumn::make('gender')->formatStateUsing(fn (bool $state): string => $state ? 'Bro.' : 'Sis.')->label(trans('Title')),
                TextColumn::make('first_name')->label(trans('First Name')),
                TextColumn::make('last_name')->label(trans('Last Name')),
                TextColumn::make('phone')->label(trans('Phone')),
                TextColumn::make('phone_app')->label(trans('phone_app')),
                TextColumn::make('extra')->label(trans('extra')),
                TextColumn::make('notify')->formatStateUsing(fn (int $state): string => 
                    $state == 0 ? 'None' : ($state == 1 ? 'Email' : ($state == 2 ? 'Other' : ''))
                    )->label(trans('Notify')),
                IconColumn::make('active')->boolean()->label(trans('Activated')),
                TextColumn::make('last_login_at')->label(trans('Last Login At')),
                TextColumn::make('last_login_ip')->label(trans('Last Login IP')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                // DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                ]),
            ])
            ->paginated(false)
            // ->summary(false)
            ;
    }
}
