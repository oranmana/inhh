<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrrequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('hrrequest_bak');
        Schema::rename('hrrequest','hrrequest_bak');
        
        Schema::create('hrrequest', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('indate',10);  
            $table->date('rqdate');
            $table->unsignedSmallInteger('jobid');  
            $table->unsignedSmallInteger('docid');  
            $table->unsignedSmallInteger('dcid');  
            $table->unsignedSmallInteger('ppid');  
            $table->text('des');
            $table->unsignedTinyInteger('state');
            $table->unsignedSmallInteger('rcid');  
            
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
        Schema::dropIfExists('hrrequest');
    }
}
