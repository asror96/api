<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TasksTable extends Migration
{
    public function up()
    {
        Schema::create('tasks',function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->foreignId('list_id')->constrained('lists')->onDelete('cascade');
            $table->foreignId('executor_user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_completed');
            $table->text('description')->nullable();
            $table->integer('urgency');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
