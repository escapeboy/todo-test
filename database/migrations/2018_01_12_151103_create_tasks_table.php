<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTasksTable extends Migration
{

    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->integer('owner_id')->unsigned()->index();
            $table->tinyInteger('priority')->default('0');
            $table->datetime('finished_on')->nullable();
        });
    }

    public function down()
    {
        Schema::drop('tasks');
    }
}