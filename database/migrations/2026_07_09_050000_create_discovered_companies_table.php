<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discovered_companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('directory_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('website')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('email')->nullable();
            $table->text('description')->nullable();
            $table->string('source'); // google_maps, search, custom_url
            $table->string('search_keyword')->nullable();
            $table->string('search_city')->nullable();
            $table->json('raw_data')->nullable(); // Full Firecrawl response for reference
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discovered_companies');
    }
};
