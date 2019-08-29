<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $TableName = 'emps';
        $date = date('Ymd');
        $BackUpName = $TableName . '_' . $date;
        
        if (Schema::hasTable($TableName)) {
            Schema::dropIfExists($BackUpName);
            Schema::rename($TableName, $BackUpName);
        }

        Schema::create($TableName, function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->unsignedMediumInteger('dirid');
            $table->string('empcode',15)->nullable();
            $table->unsignedSmallInteger('cm');
            $table->string('nm',20)->nullable();
            $table->string('name',80)->nullable();
            $table->string('thname',80)->nullable();
            $table->date('indate');
            $table->double('retireage',5,2)->defult('55');
            $table->date('xdate')->nullable();
            $table->unsignedTinyInteger('qcode');
            $table->date('qdate')->nullable();
            $table->string('bplace',255)->nullable();
            
            $table->unsignedSmallInteger('relq');
            $table->text('address');    // Resident
            $table->unsignedSmallInteger('house');
            $table->string('tel')->nullable();
            $table->string('mobile')->nullable();
            $table->unsignedSmallInteger('edu');
            $table->string('car',20)->nullable();
            $table->string('blood',5)->nullable();
            $table->string('weight',5)->nullable();
            $table->string('height',5)->nullable();
            $table->unsignedSmallInteger('military');

            $table->string('cls',5)->nullable();
            $table->unsignedSmallInteger('orgid');
            $table->unsignedSmallInteger('posid');
            $table->unsignedSmallInteger('jobid');
            $table->string('ccid',15)->nullable();

            $table->date('pvdate')->nullable();
            $table->string('pvcode')->nullable();
            $table->unsignedTinyInteger('pvcom');
            $table->unsignedTinyInteger('pvemp');
            $table->date('pvxdate')->nullable();

            $table->unsignedDecimal('pwage', 8, 2);
            $table->unsignedDecimal('ppost', 8, 2);
            $table->unsignedDecimal('pfood', 8, 2);
            $table->unsignedDecimal('phouse', 8, 2);
            $table->unsignedDecimal('pcls', 8, 2);
            $table->unsignedDecimal('plive', 8, 2);
            $table->unsignedDecimal('pfuel', 8, 2);
            $table->unsignedDecimal('pedu', 8, 2);
            $table->unsignedDecimal('pdq', 8, 2);
            $table->unsignedDecimal('pmove', 8, 2);

            $table->string('taxcode')->nullable();
            $table->unsignedTinyInteger('sex');
            $table->unsignedSmallInteger('nation');
            $table->date('bdate')->nullable();
            $table->text('haddr');
            $table->string('cardno',10)->nullable();

            $table->unsignedSmallInteger('CREATED_BY');
            $table->datetime('CREATED_AT');
            $table->unsignedSmallInteger('UPDATED_BY');
            $table->timestamp('UPDATED_AT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emps');
    }
}
