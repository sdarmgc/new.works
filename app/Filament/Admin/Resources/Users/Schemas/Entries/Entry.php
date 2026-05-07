<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Schemas\Entries;

abstract class Entry
{
    abstract public static function make(): \Filament\Infolists\Components\Entry;
}
