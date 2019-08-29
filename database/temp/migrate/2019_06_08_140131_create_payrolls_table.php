<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('payrolls_bak');
        Schema::rename('payrolls','payrolls_bak');
        
        Schema::create('payrolls', function (Blueprint $table) {
            $table->SmallIncrements('id');
            $table->string('mth');
            $table->unsignedTinyInteger('num');  
            $table->unsignedSmallInteger('grp_id');
            $table->date('wagefor');
            $table->date('wageto');
            $table->unsignedTinyInteger('wageonly');
            $table->date('otfor');
            $table->date('otto');
            $table->date('payon');
            $table->unsignedTinyInteger('state');
            $table->string('erp_payby',1)->default('T');
            $table->string('erp_vendor',1)->default('8001228');
            $table->unsignedDecimal('erp_payroll', 11, 2);

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
        Schema::dropIfExists('payrolls');
    }
}
