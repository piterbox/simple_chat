<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('chats')){
            Schema::create('chats', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('session_id');
                $table->unsignedInteger('message_id');
                $table->unsignedInteger('user_id');
                $table->dateTime('read_at')->nullable();
                $table->boolean('type');
                $table->timestamps();
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
        Schema::dropIfExists('chats');
    }
}