<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Users\Tables\Columns;

abstract class Column
{
    abstract public static function make(): \Filament\Tables\Columns\Column;
}
