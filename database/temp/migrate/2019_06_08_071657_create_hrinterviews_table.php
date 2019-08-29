<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrinterviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('hrinterviews_bak');
        Schema::rename('hrinterviews','hrinterviews_bak');

        Schema::create('hrinterviews', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('appid');  
            $table->unsignedSmallInteger('empw');  
            $table->unsignedDecimal('s1', 5, 2);
            $table->unsignedDecimal('s2', 5, 2);
            $table->unsignedDecimal('s3', 5, 2);
            $table->unsignedDecimal('s4', 5, 2);
            $table->unsignedDecimal('s5', 5, 2);
            $table->unsignedTinyInteger('accept');  
            $table->text('rem');

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
        Schema::dropIfExists('hrinterviews');
    }
}
