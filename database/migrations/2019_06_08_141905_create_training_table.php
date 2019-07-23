<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('training_bak');
        Schema::rename('training','training_bak');
        
        Schema::create('training', function (Blueprint $table) {
            $table->SmallIncrements('id');
            $table->string('code',10);
            $table->unsignedSmallInteger('category_id');
            $table->TinyInteger('inside');
            $table->date('ondate');
            $table->date('todate')->nullable();
            $table->unsignedDecimal('hours', 5, 2);
            $table->unsignedSmallInteger('organize_id');
            $table->string('coursename');
            $table->text('place');
            $table->text('remark');
            $table->unsignedDecimal('amt_fees', 7, 2);
            $table->unsignedDecimal('amt_expenses', 7, 2);
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
        Schema::dropIfExists('training');
    }
}
