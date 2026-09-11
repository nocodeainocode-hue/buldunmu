<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('posts', 'directory_id')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('directory_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('posts', 'directory_id')) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('directory_id')->nullable(false)->change();
        });
    }
};
