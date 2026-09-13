<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listing_requests', function (Blueprint $table): void {
            $table->foreignId('claim_company_id')
                ->nullable()
                ->after('directory_id')
                ->constrained('companies')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('listing_requests', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('claim_company_id');
        });
    }
};
