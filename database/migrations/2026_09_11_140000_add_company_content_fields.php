<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['services', 'why_us_items', 'external_links'] as $column) {
            if (! Schema::hasColumn('companies', $column)) {
                Schema::table('companies', function (Blueprint $table) use ($column): void {
                    $table->json($column)->nullable();
                });
            }
        }
    }

    public function down(): void
    {
        $columns = collect(['services', 'why_us_items', 'external_links'])
            ->filter(fn (string $column): bool => Schema::hasColumn('companies', $column))
            ->all();

        if ($columns !== []) {
            Schema::table('companies', function (Blueprint $table) use ($columns): void {
                $table->dropColumn($columns);
            });
        }
    }
};
