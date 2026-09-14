<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table): void {
            $table->string('campaign_title')->default('100 Firma Rehberinde Yayın Projesi')->after('show_membership_plans');
            $table->decimal('campaign_price', 10, 2)->default(4900)->after('campaign_title');
            $table->string('campaign_whatsapp')->default('905345957147')->after('campaign_price');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table): void {
            $table->dropColumn(['campaign_title', 'campaign_price', 'campaign_whatsapp']);
        });
    }
};
