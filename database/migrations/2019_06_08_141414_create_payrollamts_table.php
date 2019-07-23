<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollamtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('payrollamts_bak');
        Schema::rename('payrollamts','payrollamts_bak');
        
        Schema::create('payrollamts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('payemp_id');
            $table->TinyInteger('plus');
            $table->unsignedSmallInteger('item_id');
            $table->unsignedDecimal('amount', 9, 2);
            $table->string('remark')->nullable();
            $table->unsignedSmallInteger('erp_gl');

            $table->unsignedSmallInteger('CREATED_BY');
            $table->unsignedSmallInteger('UPDATED_BY');
            $table->timestamps();

            $table->unsignedSmallInteger('ps_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payrollamts');
    }
}
