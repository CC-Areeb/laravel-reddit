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
        Schema::create('communities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->nullable();
            $table->string('description')->nullable();
            $table->string('banner')->nullable();
            $table->longText('rules')->nullable();
            $table->string('theme')->nullable();
            $table->unsignedBigInteger('type')->nullable();
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communities');
    }
};
