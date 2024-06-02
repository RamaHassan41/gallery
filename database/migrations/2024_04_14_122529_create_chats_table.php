<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->dateTime('send_date')->format('Y/m/d H:i:s');
            $table->dateTime('recieve_date')->format('Y/m/d H:i:s');
            $table->unsignedBigInteger('sender_id');
            // $table->foreign('sender_id')->references('id')->on('artists')->orOn('users')
            //         ->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('reciever_id');
            // $table->foreign('reciever_id')->references('id')->on('artists')->orOn('users')
            //         ->onUpdate('cascade')->onDelete('cascade');
            $table->string('sender_type');
            $table->string('reciever_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
