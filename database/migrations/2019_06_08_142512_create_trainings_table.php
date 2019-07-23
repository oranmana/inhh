<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('trainings_bak');
        Schema::rename('trainings','trainings_bak');
        
        Schema::create('trainings', function (Blueprint $table) {
            $table->MediumIncrements('id');
            $table->unsignedSmallInteger('trin_id');
            $table->unsignedSmallInteger('emp_id');
            $table->unsignedDecimal('trainhours', 5, 2);
            $table->date('traindate');
            $table->unsignedDecimal('fees', 7, 2);
            $table->unsignedDecimal('expenses', 7, 2);
            $table->unsignedSmallInteger('leaverq_id');

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
        Schema::dropIfExists('trainings');
    }
}
