<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTasksRelationsTable extends Migration
{

    public function up()
    {
        Schema::create('tasks_relations', function (Blueprint $table) {
            $table->integer('tasks_id')->unsigned()->index();
            $table->integer('relation_id')->unsigned()->index();
            $table->string('relation_type');
            $table->string('connection')->nullable();
        });
    }

    public function down()
    {
        Schema::drop('tasks_relations');
    }
}