<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('discovered_companies', function (Blueprint $table) {
            $table->string('external_id')->nullable()->after('name');
            $table->string('source_url')->nullable()->after('source');
            $table->decimal('latitude', 10, 7)->nullable()->after('address');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('opening_hours')->nullable()->after('longitude');
            $table->index(['directory_id', 'source', 'external_id']);
        });
    }

    public function down(): void
    {
        Schema::table('discovered_companies', function (Blueprint $table) {
            $table->dropIndex(['directory_id', 'source', 'external_id']);
            $table->dropColumn(['external_id', 'source_url', 'latitude', 'longitude', 'opening_hours']);
        });
    }
};
