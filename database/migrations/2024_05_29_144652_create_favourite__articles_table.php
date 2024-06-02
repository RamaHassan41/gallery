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
        Schema::create('favourite__articles', function (Blueprint $table) {
            $table->id();
            $table->dateTime('adding_date')->format('Y/m/d H:i:s');
            $table->unsignedBigInteger('article_id');
            $table->foreign('article_id')->references('id')->on('articles');
            $table->unsignedBigInteger('favourite_id');
            $table->foreign('favourite_id')->references('id')->on('favourites');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favourite__articles');
    }
};
