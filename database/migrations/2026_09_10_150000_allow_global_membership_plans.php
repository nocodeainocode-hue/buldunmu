<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('membership_plans', function (Blueprint $table) {
            $table->foreignId('directory_id')->nullable()->change();
        });

        DB::statement(
            'CREATE UNIQUE INDEX IF NOT EXISTS membership_plans_global_slug_unique '
            . 'ON membership_plans (slug) WHERE directory_id IS NULL'
        );
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS membership_plans_global_slug_unique');

        Schema::table('membership_plans', function (Blueprint $table) {
            $table->foreignId('directory_id')->nullable(false)->change();
        });
    }
};
