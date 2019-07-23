<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSapItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('sap_item_bak');
        Schema::rename('sap_item', 'sap_item_bak');

        Schema::create('sap_item', function (Blueprint $table) {
            $table->SmallIncrements('itm_id');
            $table->string('itm_code',12)->nullable();
            $table->string('itm_name')->nullable();
            $table->string('itm_ba',4)->nullable();
            $table->string('itm_type',4)->nullable();
            $table->unsignedTinyInteger('itm_sub');
            $table->string('itm_grp',5)->nullable();
            $table->string('itm_plant',4)->nullable();
            $table->string('itm_vc',4)->nullable();
            $table->unsignedTinyInteger('itm_dcl');
            $table->string('itm_coa',4)->nullable();
            $table->unsignedTinyInteger('itm_pj');
            $table->string('itm_p1',20)->nullable();
            $table->string('itm_p2',20)->nullable();
            $table->string('itm_p3',20)->nullable();
            $table->unsignedDecimal('itm_pk', 7, 3);
            $table->string('itm_uom',5)->nullable();
            $table->unsignedSmallInteger('i_uom');
            $table->string('itm_cname')->nullable();
            $table->unsignedDecimal('itm_wdm', 7, 3);
            $table->unsignedDecimal('itm_kg', 7, 3);
            $table->string('FacGroup',50)->nullable();
            $table->unsignedDecimal('itm_prc', 7, 2);
            $table->unsignedDecimal('itm_c3', 7, 2);
            $table->unsignedDecimal('itm_c6', 7, 2);

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
        Schema::dropIfExists('sap_item');
    }
}
