<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExcelData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('excel_data', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('file_id');
            $table->mediumText('value');
            $table->integer('sheet_index');
            $table->integer('col');
            $table->integer('row');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
