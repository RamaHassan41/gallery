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
        Schema::create('painting_evaluations', function (Blueprint $table) {
            $table->id();
            $table->integer('degree')->nullable()->default(0)->between(0,5);
            $table->dateTime('date')->format('Y/m/d H:i:s');
            $table->unsignedBigInteger('rater_id');
            // $table->foreign('rater_id')->references('id')->on('users')->orOn('artists')
            //         ->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('painting_id');
            $table->foreign('painting_id')->references('id')->on('paintings')
                    ->onUpdate('cascade')->onDelete('cascade');
            $table->string('rater_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('painting_evaluations');
    }
};
