<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/countries.sql');
        if (!file_exists($path)) {
            throw new \Exception("SQL file not found at: {$path}");
        }
        $sql = File::get($path);
        DB::unprepared($sql);
    }
}
