<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_offerings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('directory_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20);
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->string('status', 20)->default('active');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['directory_id', 'company_id', 'status']);
        });

        Schema::create('job_postings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('directory_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->nullable()->unique();
            $table->text('description');
            $table->string('employment_type', 30);
            $table->string('location')->nullable();
            $table->string('apply_email')->nullable();
            $table->string('apply_url')->nullable();
            $table->string('status', 20)->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('admin_published')->default(false);
            $table->timestamps();
            $table->index(['directory_id', 'status', 'expires_at']);
            $table->index(['company_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_postings');
        Schema::dropIfExists('company_offerings');
    }
};
