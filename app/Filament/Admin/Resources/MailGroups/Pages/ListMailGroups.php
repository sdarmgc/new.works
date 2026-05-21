<?php

namespace App\Filament\Admin\Resources\MailGroups\Pages;

use App\Filament\Admin\Resources\MailGroups\MailGroupResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMailGroups extends ListRecords
{
    protected static string $resource = MailGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
