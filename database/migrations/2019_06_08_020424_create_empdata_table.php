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
        Schema::dropIfExists('empdata_bak');
        Schema::rename('empdata', 'empdata_bak');

        Schema::create('empdata', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedTinyInteger('grp');  // Type of data : relatives, education
            $table->unsignedSmallInteger('docid');
            $table->unsignedSmallInteger('code');
            $table->unsignedSmallInteger('ref');
            $table->unsignedSmallInteger('yr');
            $table->string('name')->nullable();
            $table->string('rem')->nullable();
            $table->string('loc')->nullable();
            $table->unsignedDecimal('grd', 8, 2);
            $table->unsignedTinyInteger('state');
            $table->unsignedSmallInteger('oid');

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
        Schema::dropIfExists('empdata');
    }
}
