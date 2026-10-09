<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listing_requests', function (Blueprint $table) {
            // Kayıt formunun 1. adımı geçildiğinde alınan yarım başvuru.
            $table->boolean('is_partial')->default(false)->after('source');
            $table->string('lead_token', 64)->nullable()->after('is_partial');
            // Başvuru anındaki reklam kaynağı (yarım kalsa bile hangi reklamdan geldiği bilinsin).
            $table->string('utm_source', 191)->nullable()->after('lead_token');
            $table->string('utm_medium', 191)->nullable()->after('utm_source');
            $table->string('utm_campaign', 191)->nullable()->after('utm_medium');
            $table->string('referrer_host', 191)->nullable()->after('utm_campaign');

            $table->index('is_partial');
            $table->index('lead_token');
        });
    }

    public function down(): void
    {
        Schema::table('listing_requests', function (Blueprint $table) {
            $table->dropIndex(['is_partial']);
            $table->dropIndex(['lead_token']);
            $table->dropColumn(['is_partial', 'lead_token', 'utm_source', 'utm_medium', 'utm_campaign', 'referrer_host']);
        });
    }
};
