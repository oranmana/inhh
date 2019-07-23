<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmppayitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('emppayitems_bak');
        Schema::rename('emppayitems', 'emppayitems_bak');

        Schema::create('emppayitems', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedSmallInteger('docid');
            $table->unsignedSmallInteger('empid');
            $table->date('indate');             // First date used
            $table->date('xdate')->nullable();  // Last date used
            $table->unsignedTinyInteger('on');  // Status active/non-active
            $table->unsignedTinyInteger('pay'); // Paytype

            $table->unsignedDecimal('wage', 8, 2);
            $table->unsignedDecimal('cls', 8, 2);
            $table->unsignedDecimal('pos', 8, 2);
            $table->unsignedDecimal('job', 8, 2);
            $table->unsignedDecimal('trans', 8, 2);
            $table->unsignedDecimal('house', 8, 2);
            $table->unsignedDecimal('food', 8, 2);
            $table->unsignedDecimal('prof', 8, 2);
            $table->unsignedDecimal('comm', 8, 2);
            $table->unsignedDecimal('onmove', 8, 2);
            
            $table->string('rem')->nullable();
            $table->unsignedSmallInteger('oldid');

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
        Schema::dropIfExists('emppayitems');
    }
}
