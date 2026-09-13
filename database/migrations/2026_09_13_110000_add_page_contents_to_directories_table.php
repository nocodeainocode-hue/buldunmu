<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('directories', 'page_contents')) {
            Schema::table('directories', function (Blueprint $table) {
                $table->json('page_contents')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('directories', 'page_contents')) {
            Schema::table('directories', function (Blueprint $table) {
                $table->dropColumn('page_contents');
            });
        }
    }
};
