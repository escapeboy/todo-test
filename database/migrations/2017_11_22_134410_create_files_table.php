<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFilesTable extends Migration
{

    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('filepath', 255);
            $table->string('extension', 10)->index();
            $table->string('mime_type', 100)->nullable()->index();
            $table->integer('size')->unsigned();
            $table->string('original_name')->nullable();
        });
    }

    public function down()
    {
        Schema::drop('files');
    }
}