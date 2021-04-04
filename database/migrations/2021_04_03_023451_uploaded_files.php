<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UploadedFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('uploaded_files')) {
            Schema::create('uploaded_files', function (Blueprint $table) {
                $table->increments('id');
                $table->string('path');
                $table->string('file_name');
                $table->string('mime');
                $table->string('original_name');
                $table->string('has_headers');
                $table->string('misc_properties')->default('{}');
                $table->integer('user_id');
                $table->timestamp('created_at');
                $table->timestamp('updated_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('uploaded_files');
    }
}
