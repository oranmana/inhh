<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHrattnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('hrattns_bak');
        Schema::rename('hrattns', 'hrattns_bak');

        Schema::create('hrattns', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('wkid');
            $table->unsignedTinyInteger('holiday');  
            $table->unsignedDecimal('w1', 9, 2);
            $table->unsignedDecimal('w2', 9, 2);
            $table->unsignedTinyInteger('lm11');  
            $table->unsignedTinyInteger('lm12');  
            $table->unsignedTinyInteger('lm21');  
            $table->unsignedTinyInteger('lm22');  
            $table->unsignedDecimal('lh1', 5, 2);
            $table->unsignedDecimal('lh2', 5, 2);
            $table->unsignedDecimal('oh1', 5, 2);
            $table->unsignedDecimal('oh2', 5, 2);
            $table->unsignedDecimal('oh3', 5, 2);
            $table->unsignedSmallInteger('lvid');  
            $table->unsignedDecimal('lvd', 4, 2);
            $table->unsignedDecimal('lvh', 5, 2);
            $table->unsignedDecimal('ot10', 5, 2);
            $table->unsignedDecimal('ot15', 5, 2);
            $table->unsignedDecimal('ot20', 5, 2);
            $table->unsignedDecimal('ot30', 5, 2);
            
            $table->unsignedDecimal('lvamt', 5, 2);
            $table->unsignedDecimal('otamt', 5, 2);
            $table->unsignedDecimal('ltamt', 5, 2);

            $table->unsignedSmallInteger('CREATED_BY');
            $table->unsignedSmallInteger('UPDATED_BY');
            $table->timestamps();         

            $table->unsignedSmallInteger('at_id');  // Old data id

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hrattns');
    }
}
