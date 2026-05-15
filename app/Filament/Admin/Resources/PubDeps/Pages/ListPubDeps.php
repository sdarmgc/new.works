<?php

namespace App\Filament\Admin\Resources\PubDeps\Pages;

use App\Filament\Admin\Resources\PubDeps\PubDepResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Schemas\Components\Tabs\Tab;

class ListPubDeps extends ListRecords
{
    protected static string $resource = PubDepResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return trans('Publication Department');
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->whereHas('roles', fn ($query) => $query->whereIn('name', ['Translator', 'pab']))
                    ->orderByDesc('id')
                )
                ->badge(fn () => static::getModel()::whereHas('roles', fn ($query) => $query->whereIn('name', ['Translator', 'pab']))
                ->whereHas('profile', fn ($query) => $query->where('active', true))
                ->count()
                ),
            'translator' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->whereHas('roles', fn ($query) => $query->where('name', 'Translator'))
                    ->orderByDesc('id')
                )
                ->badge(fn () => static::getModel()::whereHas('roles', fn ($query) => $query->where('name', 'Translator'))
                ->whereHas('profile', fn ($query) => $query->where('active', true))
                ->count()
                ),
            'pab' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->whereHas('roles', fn ($query) => $query->where('name', 'pab'))
                    ->orderByDesc('id')
                )
                ->badge(fn () => static::getModel()::whereHas('roles', fn ($query) => $query->where('name', 'pab'))
                ->whereHas('profile', fn ($query) => $query->where('active', true))
                ->count()
                ),
        ];
    }
}
