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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('notification_text');
            $table->dateTime('recieve_date')->format('Y/m/d H:i:s');
            $table->unsignedBigInteger('reciever_id');
            // $table->foreign('reciever_id')->references('id')->on('artists')->orOn('users')
            //         ->onUpdate('cascade')->onDelete('cascade');
            $table->string('reciever_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
