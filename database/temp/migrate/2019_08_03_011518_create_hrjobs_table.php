<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrjobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hrjobs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedMediumInteger('empid');
            $table->unsignedMediumInteger('jobid');
            $table->date('since')->nullable();
            $table->unsignedTinyInteger('state');   // 0:Unused, 1:Main Active, 2...: Active

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
        Schema::dropIfExists('hrjobs');
    }
}
