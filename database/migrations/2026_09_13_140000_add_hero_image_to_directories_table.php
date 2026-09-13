<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('directories', 'hero_image')) {
            Schema::table('directories', function (Blueprint $table) {
                $table->string('hero_image')->nullable()->after('favicon');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('directories', 'hero_image')) {
            Schema::table('directories', function (Blueprint $table) {
                $table->dropColumn('hero_image');
            });
        }
    }
};
