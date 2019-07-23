<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrcontractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('hrcontracts_bak');
        Schema::rename('hrcontracts', 'hrcontracts_bak');

        Schema::create('hrcontracts', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedTinyInteger('grp');  
            $table->string('code',10);
            $table->unsignedSmallInteger('appid');  
            $table->unsignedSmallInteger('docid');  
            $table->unsignedSmallInteger('dcid');  
            $table->unsignedMediumInteger('dirid');  
            $table->unsignedSmallInteger('empid');  
            $table->unsignedSmallInteger('posid');  
            $table->unsignedSmallInteger('jobid');  
            $table->date('indate');
            $table->date('todate')->nullable();
            $table->string('cls')->nullable();
            $table->unsignedDecimal('amt', 9, 2);
            $table->unsignedDecimal('clsamt', 7, 2);
            $table->unsignedDecimal('posamt', 7, 2);
            $table->unsignedDecimal('jobamt', 7, 2);

            $table->unsignedSmallInteger('empsign');  
            $table->unsignedSmallInteger('empwit1');  
            $table->unsignedSmallInteger('empwit2');  

            $table->unsignedTinyInteger('state');  

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
        Schema::dropIfExists('hrcontracts');
    }
}
