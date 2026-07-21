<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite enum değiştirmeyi desteklemez, tabloyu yeniden oluşturmayacağız.
        // Bunun yerine CHECK constraint'i geçici olarak bypass edip seed yapacağız.
        // Ama önce unique index'i düzeltelim:
        
        Schema::table('membership_plans', function (Blueprint $table) {
            // Unique constraint'i kaldır (global slug unique)
            try {
                $table->dropUnique('membership_plans_slug_unique');
            } catch (\Exception $e) {
                // Index zaten yoksa sorun değil
            }
            // Directory başına unique slug
            $table->unique(['slug', 'directory_id']);
        });
    }

    public function down(): void
    {
        Schema::table('membership_plans', function (Blueprint $table) {
            $table->dropUnique('membership_plans_slug_directory_id_unique');
            $table->unique('slug');
        });
    }
};
