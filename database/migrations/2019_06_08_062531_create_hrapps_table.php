<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrappsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('hrapps_bak');
        Schema::rename('hrapps', 'hrapps_bak');

        Schema::create('hrapps', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('rqid');
            $table->unsignedMeniumInteger('dirid');  
            $table->date('wdate'); 
            $table->text('rem'); 
            $table->unsignedDecimal('amt', 9, 2);

            $table->date('indate')->nullable();
            $table->unsignedTinyInteger('score');  
            $table->unsignedTinyInteger('state');  
            $table->unsignedSmallInteger('docid');  
            $table->unsignedSmallInteger('rcid');  
            $table->unsignedSmallInteger('ppid');  
            $table->unsignedSmallInteger('dcid');  

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
        Schema::dropIfExists('hrapps');
    }
}
