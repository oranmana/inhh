<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('assetlogs_bak');
        Schema::rename('assetlogs', 'assetlogs_bak');

        Schema::create('assetlogs', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('assetid');  
            $table->date('actiondate');             
            $table->string('actiondoc')->nullable();
            $table->unsignedTinyInteger('oldstate');  
            $table->unsignedSmallInteger('oldlocationid');  
            $table->unsignedSmallInteger('oldpicid');  
            $table->unsignedSmallInteger('oldteamid');  

            $table->unsignedTinyInteger('newstate');  
            $table->unsignedSmallInteger('newlocationid');  
            $table->unsignedSmallInteger('newpicid');  
            $table->unsignedSmallInteger('newteamid');  

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
        Schema::dropIfExists('assetlogs');
    }
}
