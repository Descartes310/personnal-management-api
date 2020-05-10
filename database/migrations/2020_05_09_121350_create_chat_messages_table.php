<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_messages', function (Blueprint $table) {
           $table->increments('id');
            $table->unsignedInteger('discussion_id');
            $table->unsignedInteger('sender_id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->text('content')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('sender_delete_at')->nullable();
            $table->timestamp('receiver_delete_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();

            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('discussion_id')->references('id')->on('chat_discussions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_messages');
    }
}
