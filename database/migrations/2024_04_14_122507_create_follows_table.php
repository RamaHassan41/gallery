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
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date')->format('Y/m/d H:i:s');
            $table->unsignedBigInteger('followed_id');
            $table->foreign('followed_id')->references('id')->on('artists')
                    ->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('follower_id');
            // $table->foreign('follower_id')->references('id')->on('users')->orOn('artists')
            //         ->onUpdate('cascade')->onDelete('cascade');
            $table->string('follower_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
