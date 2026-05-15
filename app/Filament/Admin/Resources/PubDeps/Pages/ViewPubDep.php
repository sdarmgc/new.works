<?php

namespace App\Filament\Admin\Resources\PubDeps\Pages;

use App\Filament\Admin\Resources\PubDeps\PubDepResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPubDep extends ViewRecord
{
    protected static string $resource = PubDepResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
