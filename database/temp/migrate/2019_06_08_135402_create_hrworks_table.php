<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('hrworks_bak');
        Schema::rename('hrworks','hrworks_bak');
        
        Schema::create('hrworks', function (Blueprint $table) {
            $table->increments('w_id');
            $table->unsignedSmallInteger('w_empid');
            $table->unsignedSmallInteger('w_empsid');
            $table->date('w_date');
            $table->unsignedSmallInteger('w_workid');
            $table->unsignedDecimal('w_ot1', 4, 2);
            $table->unsignedDecimal('w_ot2', 4, 2);
            $table->unsignedDecimal('w_ot3', 4, 2);
            $table->unsignedSmallInteger('w_lvid');
            $table->string('w_rem')->nullable();
            $table->unsignedTinyInteger('state');  
            $table->datetime('tin')->nullable();
            $table->datetime('tout')->nullable();
            $table->datetime('tin1')->nullable();
            $table->datetime('tout2')->nullable();

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
        Schema::dropIfExists('hrworks');
    }
}
