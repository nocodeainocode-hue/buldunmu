<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_owners', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('directory_id')->constrained()->cascadeOnDelete();
            $table->string('role')->default('owner');
            $table->string('status')->default('active');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'user_id']);
            $table->index(['user_id', 'directory_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_owners');
    }
};
