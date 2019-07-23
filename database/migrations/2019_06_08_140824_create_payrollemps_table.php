<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollempsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('payrollemps_bak');
        Schema::rename('payrollemps','payrollemps_bak');
        
        Schema::create('payrollemps', function (Blueprint $table) {
            $table->MediumIncrements('id');
            $table->unsignedSmallInteger('payroll_id');
            $table->unsignedSmallInteger('emp_id');
            $table->unsignedSmallInteger('rate_id');
            $table->unsignedSmallInteger('org_id');
            $table->unsignedSmallInteger('pos_id');
            $table->unsignedTinyInteger('fullwork');
            $table->string('erp_cc',10)->nullable();
            $table->string('erp_ba',10)->nullable();

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
        Schema::dropIfExists('payrollemps');
    }
}
