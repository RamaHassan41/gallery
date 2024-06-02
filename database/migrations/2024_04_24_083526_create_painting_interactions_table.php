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
        Schema::create('painting_interactions', function (Blueprint $table) {
            $table->id();
            $table->enum('type',['like','dislike']);
            $table->dateTime('date')->format('Y/m/d H:i:s');
            $table->unsignedBigInteger('reactant_id');
            // $table->foreign('reactant_id')->references('id')->on('users')->orOn('artists')
            //         ->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('painting_id');
            $table->foreign('painting_id')->references('id')->on('paintings')
                    ->onUpdate('cascade')->onDelete('cascade');
            $table->string('reactant_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('painting_interactions');
    }
};
