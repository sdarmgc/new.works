<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\Users\UserResource;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'active' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->whereHas('profile', fn ($query) => $query->where('active', true))
                )
                ->badge(fn () => \App\Models\User::whereHas('profile', fn ($query) => $query->where('active', true))->count()
                ),
            'inactive' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->whereHas('profile', fn ($query) => $query->where('active', false))
                )
                ->badge(fn () => \App\Models\User::whereHas('profile', fn ($query) => $query->where('active', false))->count()
                ),
            'deleted' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed())
                ->badge(fn () => static::getModel()::onlyTrashed()->count()),
            'etc' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->doesntHave('profile'))
                ->badge(fn () => static::getModel()::doesntHave('profile')->count()),
        ];
    }
}
