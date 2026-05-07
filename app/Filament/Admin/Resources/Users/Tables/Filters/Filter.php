<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Filters;

abstract class Filter
{
    abstract public static function make(): \Filament\Tables\Filters\BaseFilter;
}
