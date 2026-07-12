<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['companies', 'categories', 'cities'] as $table) {
            $this->ensureTenantSlugIndex($table);
        }

        if (!Schema::hasTable('company_import_batches')) {
            Schema::create('company_import_batches', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('filename');
                $table->string('stored_path');
                $table->string('status')->default('pending')->index();
                $table->string('duplicate_strategy')->default('skip');
                $table->string('default_status')->default('pending');
                $table->json('options')->nullable();
                $table->json('stats')->nullable();
                $table->json('errors')->nullable();
                $table->timestamp('started_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasColumn('companies', 'external_id')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->string('external_id')->nullable()->after('slug');
                $table->index(['directory_id', 'external_id']);
            });
        }
        if (!Schema::hasColumn('companies', 'import_batch_id')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->foreignId('import_batch_id')->nullable()->after('external_id')
                    ->constrained('company_import_batches')->nullOnDelete();
            });
        }

        if (!Schema::hasTable('company_import_changes')) {
            Schema::create('company_import_changes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('batch_id')->constrained('company_import_batches')->cascadeOnDelete();
                $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('directory_id')->nullable()->constrained()->nullOnDelete();
                $table->string('action');
                $table->json('before_data')->nullable();
                $table->json('after_data')->nullable();
                $table->timestamp('created_at');
                $table->index(['batch_id', 'action']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('company_import_changes');
        Schema::table('companies', function (Blueprint $table) {
            $table->dropConstrainedForeignId('import_batch_id');
            $table->dropIndex(['directory_id', 'external_id']);
            $table->dropColumn('external_id');
        });
        Schema::dropIfExists('company_import_batches');
        foreach (['companies', 'categories', 'cities'] as $table) {
            $this->restoreGlobalSlugIndex($table);
        }
    }

    private function ensureTenantSlugIndex(string $table): void
    {
        $oldIndex = "{$table}_slug_unique";
        $newIndex = "{$table}_directory_id_slug_unique";

        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE \"{$table}\" DROP CONSTRAINT IF EXISTS \"{$oldIndex}\"");
            DB::statement("DROP INDEX IF EXISTS \"{$oldIndex}\"");
            DB::statement("CREATE UNIQUE INDEX IF NOT EXISTS \"{$newIndex}\" ON \"{$table}\" (\"directory_id\", \"slug\")");
            return;
        }

        $indexes = collect(Schema::getIndexes($table))->pluck('name');
        if ($indexes->contains($oldIndex)) {
            Schema::table($table, fn(Blueprint $blueprint) => $blueprint->dropUnique($oldIndex));
        }
        if (!$indexes->contains($newIndex)) {
            Schema::table($table, fn(Blueprint $blueprint) => $blueprint->unique(['directory_id', 'slug']));
        }
    }

    private function restoreGlobalSlugIndex(string $table): void
    {
        $oldIndex = "{$table}_slug_unique";
        $newIndex = "{$table}_directory_id_slug_unique";

        if (DB::getDriverName() === 'pgsql') {
            DB::statement("DROP INDEX IF EXISTS \"{$newIndex}\"");
            DB::statement("CREATE UNIQUE INDEX IF NOT EXISTS \"{$oldIndex}\" ON \"{$table}\" (\"slug\")");
            return;
        }

        $indexes = collect(Schema::getIndexes($table))->pluck('name');
        if ($indexes->contains($newIndex)) {
            Schema::table($table, fn(Blueprint $blueprint) => $blueprint->dropUnique(['directory_id', 'slug']));
        }
        if (!$indexes->contains($oldIndex)) {
            Schema::table($table, fn(Blueprint $blueprint) => $blueprint->unique('slug'));
        }
    }
};
