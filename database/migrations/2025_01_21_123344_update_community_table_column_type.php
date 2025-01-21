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
        Schema::table('communities', function (Blueprint $table) {
            $table->dropColumn('banner');
            $table->dropColumn('theme');
            $table->json('banner_theme')->nullable()->after('description');
            $table->string('subreddit_logo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('communities', function (Blueprint $table) {
            $table->string('banner')->nullable();
            $table->string('theme')->nullable();
            $table->dropColumn('banner_theme');
            $table->dropColumn('subreddit_logo');
        });
    }
};
