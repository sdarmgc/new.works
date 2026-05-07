<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Admin\Resources\Users\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string
    {
        return 'Create';
    }
}
