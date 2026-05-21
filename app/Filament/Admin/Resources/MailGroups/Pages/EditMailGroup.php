<?php

namespace App\Filament\Admin\Resources\MailGroups\Pages;

use App\Filament\Admin\Resources\MailGroups\MailGroupResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMailGroup extends EditRecord
{
    protected static string $resource = MailGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
