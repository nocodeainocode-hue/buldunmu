<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
            $table->foreignId('directory_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('anchor_text')->nullable();
            $table->enum('link_type', ['dofollow', 'nofollow', 'sponsored', 'ugc'])->default('dofollow');
            $table->timestamp('scheduled_for')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->enum('status', ['scheduled', 'published', 'failed', 'skipped'])->default('scheduled');
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_items');
    }
};