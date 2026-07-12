<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('directories', function (Blueprint $table) {
            $table->string('geography_mode')->default('national')->after('slug_pattern');
            $table->string('primary_city_slug')->nullable()->after('geography_mode');
            $table->json('featured_city_slugs')->nullable()->after('primary_city_slug');
            $table->boolean('group_other_cities')->default(true)->after('featured_city_slugs');
        });
    }

    public function down(): void
    {
        Schema::table('directories', function (Blueprint $table) {
            $table->dropColumn(['geography_mode', 'primary_city_slug', 'featured_city_slugs', 'group_other_cities']);
        });
    }
};
