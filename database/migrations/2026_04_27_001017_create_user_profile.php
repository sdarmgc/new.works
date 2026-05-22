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
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes(); // Adds the deleted_at column
        });

        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->integer('gender')->default(0)->comment('1="Br.", 2="Sis."');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('responsibility', 127)->nullable();
            $table->string('extra')->nullable();
            $table->boolean('notify')->default(0)->comment('0:None, 1:email, 2:sms');
            $table->tinyInteger('active')->default(0)->unsigned();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->timestamps();
        });

        DB::table('old_users')->orderBy('id')->chunkById(500, function ($old_users) {
            $users = [];
            $profiles = [];
            $roles = [];
            $languages = [];

            foreach ($old_users as $user) {
                $users[] = [
                    'id' => $user->id,
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'email' => $user->email,
                    'email_verified_at' => '2026-01-01 00:00:00',
                    'password' => $user->password,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                    'deleted_at' => $user->deleted_at,
                ];
                $profiles[] = [
                    'user_id' => $user->id,
                    'gender' => (trim($user->title) == 'Sis.') ? 2 : 1,
                    'first_name' => $user->first_name,
                    'last_name'  => $user->last_name,
                    'phone' => $user->phone,
                    'responsibility' => $user->role,
                    'extra' => $user->extra,
                    'notify' => $user->notify,
                    'active' => $user->active,
                    'last_login_at' => $user->last_login_at,
                    'last_login_ip' => $user->last_login_ip,
                ];
                $langs = explode(',', trim($user->language));
                foreach ($langs as $lang) {
                    $id = DB::table('languages')->where('iso_639_1', $lang)->value('id');
                    if ($id) {
                        $languages[] = [
                            'user_id' => $user->id,
                            'language_id' => $id
                        ];
                    }
                }
            }

            DB::table('users')->insert($users);
            DB::table('user_profiles')->insert($profiles);
            DB::table('user_language')->insert($languages);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profiles');
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Removes the column if rolled back
        });
    }
};
