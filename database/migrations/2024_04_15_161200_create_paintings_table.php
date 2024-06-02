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
        Schema::create('paintings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('size')->nullable();
            $table->float('price')->nullable();
            $table->dateTime('creation_date')->format('Y/m/d H:i:s');
            $table->string('url');
            $table->unsignedBigInteger('artist_id');
            $table->foreign('artist_id')->references('id')->on('artists')
                    ->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')->references('id')->on('types');
            $table->unsignedBigInteger('archive_id')->nullable();
            $table->foreign('archive_id')->references('id')->on('archives')
                    ->onUpdate('cascade');
            $table->integer('interactions_number')->default(0);
            $table->integer('comments_number')->default(0);
            $table->integer('rates_number')->default(0);
            $table->integer('reports_number')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paintings');
    }
};
