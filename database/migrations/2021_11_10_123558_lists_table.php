<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ListsTable extends Migration
{
    public function up()
    {
       Schema::create('lists',function (Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->integer('count_tasks')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->boolean('is_closed')->default(false);
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }

    public function down()
    {
       Schema::dropIfExists('lists');
    }
}
