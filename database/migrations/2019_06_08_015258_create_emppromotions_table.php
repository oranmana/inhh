<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmppromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('emppromotions_bak');
        Schema::rename('emppromotions', 'emppromotions_bak');

        Schema::create('emppromotions', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('docid');
            $table->unsignedSmallInteger('empid');
            $table->date('indate');
            $table->date('xdate')->nullable();
            $table->unsignedTinyInteger('on');
            $table->string('cls',5)->nullable();
            $table->unsignedSmallInteger('posid');
            $table->unsignedSmallInteger('orgid');
            $table->unsignedSmallInteger('jobid');
            $table->string('rem',5)->nullable();
            $table->unsignedSmallInteger('old');

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
        Schema::dropIfExists('emppromotions');
    }
}
