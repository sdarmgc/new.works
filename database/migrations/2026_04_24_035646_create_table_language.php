<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Database\Seeders\LanguageSeeder; 

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('family')->nullable();
            $table->string('language_name');
            $table->string('native_name')->nullable();
            $table->string('iso_639_1')->unique();
            $table->string('iso_639_2t')->nullable();
            $table->string('iso_639_2b')->nullable();
            $table->string('iso_639_3')->nullable();
            $table->string('iso_639_6')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });

        $seeder = new LanguageSeeder();
        $seeder->run();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('languages');
    }
};
