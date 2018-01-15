<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFilesRelationsTable extends Migration
{

    public function up()
    {
        Schema::create('files_relations', function (Blueprint $table) {
            $table->integer('files_id')->unsigned()->index();
            $table->integer('relation_id')->unsigned()->index();
            $table->string('relation_type', 255);
            $table->string('file_type', 50)->nullable()->index();
        });
    }

    public function down()
    {
        Schema::drop('files_relations');
    }
}