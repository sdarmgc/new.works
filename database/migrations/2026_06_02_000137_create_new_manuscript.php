<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewManuscript extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manuscripts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('category')->default("");
            $table->string('name')->unique();
            $table->string('view_class')->comment('Display class')->default("");
            $table->boolean('active')->default(false);
            $table->integer('sort')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });
        Schema::create('manuscript_items', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('manuscript_id')->constrained('manuscripts')->cascadeOnDelete(); 
            $table->integer('type')->default(1)->comment('1:Translator, 2:Html Link, 3:Text File, 4:PDF, 5:Image File, 6: Zipped File, 7: otehr file');  
            $table->string('name')->unique();
            $table->string('description', 255)->comment('Description');  
            $table->string('url')->comment('Web URL or file path(name)');
            $table->integer('size');
            $table->integer('sort')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manuscripts');
        Schema::dropIfExists('manuscript_items');
    }
}
