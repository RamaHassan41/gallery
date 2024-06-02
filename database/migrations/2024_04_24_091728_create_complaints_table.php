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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->enum('status',['accepted','rejected'])->default('rejected');
            $table->dateTime('date')->format('Y/m/d H:i:s');
            $table->unsignedBigInteger('reporter_id');
            $table->unsignedBigInteger('reported_id');
            // $table->unsignedBigInteger('admin_id');
            // $table->foreign('admin_id')->references('id')->on('admins')
            //         ->onUpdate('cascade')->onDelete('cascade');
            //$table->unique(['reporter_id', 'reported_id']);
            $table->string('reporter_type');
            $table->string('reported_type');
            $table->timestamps();
        });



            // $table->unsignedBigInteger('painting_id');
            // $table->foreign('painting_id')->references('id')->on('paintings');

            // $table->unsignedBigInteger('article_id');
            // $table->foreign('article_id')->references('id')->on('articles');

            // $table->unsignedBigInteger('article_comment_id');
            // $table->foreign('article_comment_id')->references('id')->on('article_comments');
            // $table->unsignedBigInteger('painting_comment_id');
            // $table->foreign('painting_comment_id')->references('id')->on('painting_comments');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
