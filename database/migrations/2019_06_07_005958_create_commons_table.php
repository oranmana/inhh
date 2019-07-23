<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('commons_bak');
        Schema::rename('commons', 'commons_bak');

        Schema::create('commons', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('par');
            $table->unsignedSmallInteger('main');
            $table->unsignedSmallInteger('num');
            $table->string('code',20)->nullable();
            $table->string('name',255)->nullable();
            $table->string('tname',255)->nullable();
            $table->string('des',255)->nullable();
            $table->unsignedSmallInteger('sub');
            $table->unsignedSmallInteger('type');
            $table->unsignedTinyInteger('group');
            $table->unsignedSmallInteger('cat');
            $table->string('ref',250)->nullable();
            $table->unsignedTinyInteger('off');
            $table->unsignedInteger('dir');
            $table->unsignedSmallInteger('gl');
            $table->unsignedSmallInteger('sfx');
            $table->string('node',256)->nullable();
            $table->unsignedSmallInteger('pj');
            $table->string('erp',15)->nullable();
            $table->unsignedSmallInteger('CREATED_BY');
            $table->unsignedSmallInteger('UPDATED_BY');
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
        Schema::dropIfExists('commons');
    }
}
