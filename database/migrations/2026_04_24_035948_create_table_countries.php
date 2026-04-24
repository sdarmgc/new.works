<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Database\Seeders\CountrySeeder; 

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('code', 2)->unique()->comment('ISO 3166-1 alpha-2');
            $table->string('phone_code', 0)->nullable()->comment('Phone code');
            $table->string('lang_code', 3)->nullable()->comment('ISO 639-3');
            $table->string('region', 50)->nullable()->comment('Continent / region');
            $table->timestamps();
        });

        $seeder = new CountrySeeder();
        $seeder->run();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
};
