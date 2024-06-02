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
        Schema::create('reliability__certificates', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('personal_image');
            $table->string('another_image')->nullable();
            $table->enum('status',['accepted','rejected'])->default('rejected');
            $table->dateTime('send_date')->format('Y/m/d H:i:s');
            $table->unsignedBigInteger('artist_id');
            $table->foreign('artist_id')->references('id')->on('artists')
                    ->onUpdate('cascade')->onDelete('cascade');
            // $table->unsignedBigInteger('admin_id');
            // $table->foreign('admin_id')->references('id')->on('admins')
            //         ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reliability__certificates');
    }
};
