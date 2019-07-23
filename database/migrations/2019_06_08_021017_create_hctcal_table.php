<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHctcalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('hctcal_bak');
        Schema::rename('hctcal', 'hctcal_bak');

        Schema::create('hctcal', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->date('cldate');             
            $table->unsignedTinyInteger('holiday');  // 0=workday,1=weekend,2=public holiday
            $table->string('rem')->nullable();

            $table->unsignedSmallInteger('of'); 
            $table->string('ofm',6)->nullable();
            $table->string('ofo',6)->nullable();

            $table->unsignedSmallInteger('wf'); 
            $table->string('wfm',6)->nullable();
            $table->string('wfo',6)->nullable();

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
        Schema::dropIfExists('hctcal');
    }
}
