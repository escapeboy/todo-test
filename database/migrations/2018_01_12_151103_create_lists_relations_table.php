<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateListsRelationsTable extends Migration
{

    public function up()
    {
        Schema::create('lists_relations', function (Blueprint $table) {
            $table->integer('lists_id')->unsigned()->index();
            $table->integer('relation_id')->unsigned()->index();
            $table->string('connection');
            $table->string('relation_type', 255);
        });
    }

    public function down()
    {
        Schema::drop('lists_relations');
    }
}