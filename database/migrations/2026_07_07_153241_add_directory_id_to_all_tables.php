<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['companies', 'categories', 'cities', 'districts', 'listing_requests', 'contact_messages', 'site_settings'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreignId('directory_id')->nullable()->after('id')->constrained()->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        $tables = ['companies', 'categories', 'cities', 'districts', 'listing_requests', 'contact_messages', 'site_settings'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropConstrainedForeignId('directory_id');
            });
        }
    }
};