<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\MailGroups\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Admin\Resources\MailGroups\MailGroupResource;

class ManageMailGroups extends ManageRecords
{
    protected static string $resource = MailGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
