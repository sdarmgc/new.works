<?php

namespace App\Filament\Admin\Resources\MailGroups\Pages;

use App\Filament\Admin\Resources\MailGroups\MailGroupResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMailGroup extends CreateRecord
{
    protected static string $resource = MailGroupResource::class;
}
