<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateListsTable extends Migration
{

    public function up()
    {
        Schema::create('lists', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->unsignedInteger('owner_id')->index();
        });
    }

    public function down()
    {
        Schema::drop('lists');
    }
}