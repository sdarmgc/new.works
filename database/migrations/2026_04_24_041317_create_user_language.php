<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_language', function (Blueprint $table) {
            $table->foreignId('user_id')->index();
            $table->foreignId('lang_id');
        });
        Schema::create('user_country', function (Blueprint $table) {
            $table->foreignId('user_id')->index();
            $table->foreignId('country_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_language');
        Schema::dropIfExists('user_country');
    }
};
