<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('directories') || ! Schema::hasTable('site_settings')) {
            return;
        }

        DB::table('directories')->orderBy('id')->eachById(function (object $directory): void {
            $exists = DB::table('site_settings')
                ->where('directory_id', $directory->id)
                ->exists();

            if (! $exists) {
                DB::table('site_settings')->insert([
                    'directory_id' => $directory->id,
                    'site_name' => $directory->name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }

    public function down(): void
    {
        // Backfilled settings may have been customized after this migration.
    }
};
