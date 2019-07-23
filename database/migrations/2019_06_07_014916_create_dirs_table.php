<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDirsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('dirs_bak');
        Schema::rename('dirs', 'dirs_bak');

        Schema::create('dirs', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->unsignedTinyInteger('type');
            $table->unsignedTinyInteger('sex');
            $table->unsignedMediumInteger('par');
            $table->string('code',20)->nullable();
            $table->unsignedSmallInteger('grp');
            $table->unsignedSmallInteger('num');
            $table->string('nm',255)->nullable();
            $table->string('name',255)->nullable();
            $table->string('tname',255)->nullable();
            $table->unsignedSmallInteger('nation');
            $table->text('address');
            $table->string('tel')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->date('bdate')->nullable();
            $table->date('xdate')->nullable();
            $table->unsignedInteger('cap');
            $table->text('md');
            $table->unsignedSmallInteger('appby');
            $table->date('appdate')->nullable();
            $table->date('validity')->nullable();
            $table->unsignedTinyInteger('iserp');
            $table->unsignedTinyInteger('erpdir');
            $table->string('erpfi',15)->nullable();
            $table->string('erpmm',15)->nullable();
            $table->string('erpsd',15)->nullable();
            $table->unsignedSmallInteger('empid');
            $table->unsignedSmallInteger('emprelative');
            $table->unsignedSmallInteger('empcat');
            $table->text('rem');
            $table->unsignedTinyInteger('state');
            $table->unsignedSmallInteger('eby');
            $table->date('eon')->nullable();
            $table->unsignedSmallInteger('zdir');
            $table->string('pic')->nullable();
            $table->string('req',40)->nullable();
            $table->string('tax',250)->nullable();
            $table->unsignedSmallInteger('cty');

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
        Schema::dropIfExists('dirs');
    }
}
