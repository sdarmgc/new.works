<?php

namespace App\Filament\Admin\Resources\PubDeps\Pages;

use App\Filament\Admin\Resources\PubDeps\PubDepResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPubDep extends EditRecord
{
    protected static string $resource = PubDepResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
