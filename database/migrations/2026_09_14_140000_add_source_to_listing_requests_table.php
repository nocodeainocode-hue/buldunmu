<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listing_requests', function (Blueprint $table): void {
            $table->string('source')->default('manual')->after('claim_company_id');
        });
    }

    public function down(): void
    {
        Schema::table('listing_requests', function (Blueprint $table): void {
            $table->dropColumn('source');
        });
    }
};
