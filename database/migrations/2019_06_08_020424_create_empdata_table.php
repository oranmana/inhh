<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpdataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $TableName = 'empsdata';
        $date = date('Ymd');
        $BackUpName = $TableName . '_' . $date;
        
        if (Schema::hasTable($TableName)) {
            Schema::dropIfExists($BackUpName);
            Schema::rename($TableName, $BackUpName);
        }

        Schema::create($TableName, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('grp');  // Type of data : 1-education, ?-relatives, ?-training
            $table->unsignedSmallInteger('empid');
            $table->unsignedSmallInteger('code');
            $table->unsignedSmallInteger('ref');
            $table->unsignedSmallInteger('yr');
            $table->string('name')->nullable();
            $table->string('rem')->nullable();
            $table->string('loc')->nullable();
            $table->unsignedDecimal('grd', 8, 2);
            $table->unsignedTinyInteger('state');

            $table->unsignedSmallInteger('CREATED_BY');
            $table->datetime('CREATED_AT');
            $table->unsignedSmallInteger('UPDATED_BY');
            $table->timestamp('UPDATED_AT');

            $table->unsignedSmallInteger('oid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empsdata');
    }
}
