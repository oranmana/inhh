<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrlvrqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('hrlvrqs_bak');
        Schema::rename('hrlvrqs','hrlvrqs_bak');
        
        Schema::create('hrlvrqs', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('empid');  
            $table->unsignedSmallInteger('lvid');  
            $table->date('fdate');
            $table->unsignedTinyInteger('num');
            $table->text('rem');
            $table->unsignedSmallInteger('trref');  
            $table->unsignedTinyInteger('state');
            
            $table->unsignedSmallInteger('requested_by');  
            $table->datetime('requested_at');  
            $table->unsignedSmallInteger('verified_by');  
            $table->datetime('verified_at')->nullable();  
            $table->unsignedSmallInteger('approved_by');  
            $table->datetime('approved_at')->nullable();  

            $table->timestamps();        
            
            $table->unsignedSmallInteger('lv_id');  
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrlvrqs');
    }
}
