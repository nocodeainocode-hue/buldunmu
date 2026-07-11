<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->unique(['directory_id', 'slug']);
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->unique(['directory_id', 'slug']);
        });
        Schema::table('cities', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->unique(['directory_id', 'slug']);
        });

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

        Schema::table('companies', function (Blueprint $table) {
            $table->string('external_id')->nullable()->after('slug');
            $table->foreignId('import_batch_id')->nullable()->after('external_id')
                ->constrained('company_import_batches')->nullOnDelete();
            $table->index(['directory_id', 'external_id']);
        });

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

    public function down(): void
    {
        Schema::dropIfExists('company_import_changes');
        Schema::table('companies', function (Blueprint $table) {
            $table->dropConstrainedForeignId('import_batch_id');
            $table->dropIndex(['directory_id', 'external_id']);
            $table->dropColumn('external_id');
        });
        Schema::dropIfExists('company_import_batches');
        Schema::table('companies', function (Blueprint $table) {
            $table->dropUnique(['directory_id', 'slug']);
            $table->unique('slug');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['directory_id', 'slug']);
            $table->unique('slug');
        });
        Schema::table('cities', function (Blueprint $table) {
            $table->dropUnique(['directory_id', 'slug']);
            $table->unique('slug');
        });
    }
};
