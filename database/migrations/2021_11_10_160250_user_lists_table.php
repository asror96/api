<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserListsTable extends Migration
{
    public function up()
    {
        Schema::create('user_lists',function (Blueprint $table)
        {
            $table->increments('id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('list_id')->constrained('lists')->onDelete('cascade');
            $table->timestamp('created_at');
            $table->timestamp('update_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_lists');
    }
}
