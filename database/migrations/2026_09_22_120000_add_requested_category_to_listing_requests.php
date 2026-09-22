<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table): void {
            $table->foreignId('category_id')->nullable()->change();
        });

        Schema::table('listing_requests', function (Blueprint $table): void {
            $table->string('requested_category')->nullable()->after('category_id');
        });
    }

    public function down(): void
    {
        Schema::table('listing_requests', function (Blueprint $table): void {
            $table->dropColumn('requested_category');
        });

        $fallbackCategoryId = DB::table('categories')->orderBy('id')->value('id');
        if ($fallbackCategoryId) {
            DB::table('companies')->whereNull('category_id')->update(['category_id' => $fallbackCategoryId]);
        }

        Schema::table('companies', function (Blueprint $table): void {
            $table->foreignId('category_id')->nullable(false)->change();
        });
    }
};
