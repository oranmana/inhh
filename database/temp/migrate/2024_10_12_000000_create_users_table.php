<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users_bak');
        Schema::rename('users','users_bak');

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',10);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('username',10);
            $table->string('log_pws',41)->default('1234');
            $table->unsignedSmallInteger('dir_id');
            $table->string('sapid',6)->nullable();
            $table->string('eagle',6)->nullable();
            $table->datetime('expiredate')->nullable();
            $table->unsignedTinyInteger('state');
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
