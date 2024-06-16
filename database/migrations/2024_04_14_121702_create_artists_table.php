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
        Schema::create('artists', function (Blueprint $table) {
            $table->id();        
            $table->string('name');
            $table->string('user_name')->nullable()->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('image')->nullable();
            $table->string('expertise')->nullable();
            $table->string('specialization')->nullable();
            $table->text('biography')->nullable();
            $table->enum('gender',['male','female'])->nullable();
            $table->string('code')->nullable();
            $table->text('device_token')->nullable();
            $table->enum('status',['activeAsUser','activeAsArtist','blocked'])
            ->default('activeAsUser');
            $table->integer('followers_number')->default(0);
            $table->integer('rates_number')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artists');
    }
};
