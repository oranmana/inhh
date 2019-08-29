<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrtimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('hrtimes_bak');
        Schema::rename('hrtimes','hrtimes_bak');
        
        Schema::create('hrtimes', function (Blueprint $table) {
            $table->bigIncrements('t_id');
            $table->string('t_empcode');
            $table->unsignedSmallInteger('t_empid');
            $table->date('t_date');
            $table->datetime('t_time');
            $table->string('t_raw');

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
        Schema::dropIfExists('hrtimes');
    }
}
