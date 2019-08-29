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
        $date = date('Ymd');
        $table = 'hctcal';
        $bak = $table.'_'.$date;
        
        if (Schema::hasTable($table) ) {
            Schema::dropIfExists($bak);
            Schema::rename($table, $bak);
        }

        Schema::create($table, function (Blueprint $table) {
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
            $table->datetime('CREATED_AT');
            $table->unsignedSmallInteger('UPDATED_BY');
            $table->timestamp('UPDATED_AT');
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
