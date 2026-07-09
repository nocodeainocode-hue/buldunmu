<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('directory_post', function (Blueprint $table) {
            $table->id();
            $table->foreignId('directory_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->unique(['directory_id', 'post_id']);
        });
        
        // Drop old directory_id from posts
        if (Schema::hasColumn('posts', 'directory_id')) {
            // SQLite doesn't support dropForeign easily
            Schema::table('posts', function (Blueprint $table) {
                // Skip for SQLite, just drop column for fresh DB
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('directory_post');
    }
};