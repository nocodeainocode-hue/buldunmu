<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type', 10)->default('house'); // paid | house
            $table->string('status', 10)->default('active'); // active | paused
            $table->json('placements'); // ["top","bottom"]
            $table->string('headline');
            $table->text('body')->nullable();
            $table->string('cta_label')->default('Detaylı bilgi');
            $table->string('link_url', 500);
            $table->string('image_path')->nullable();
            $table->string('bg_color', 9)->default('#14213d');
            $table->string('text_color', 9)->default('#ffffff');
            $table->string('accent_color', 9)->default('#ffc233');
            $table->json('directory_ids')->nullable(); // boş = tüm rehberler
            $table->json('city_ids')->nullable();      // boş = her yer
            $table->json('category_ids')->nullable();  // boş = her kategori
            $table->unsignedSmallInteger('weight')->default(1);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedBigInteger('impressions_total')->default(0);
            $table->unsignedBigInteger('clicks_total')->default(0);
            $table->timestamps();

            $table->index(['status', 'starts_at', 'ends_at']);
        });

        Schema::create('ad_daily_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_campaign_id')->constrained('ad_campaigns')->cascadeOnDelete();
            $table->date('date');
            $table->unsignedBigInteger('directory_id')->default(0);
            $table->unsignedInteger('impressions')->default(0);
            $table->unsignedInteger('clicks')->default(0);

            $table->unique(['ad_campaign_id', 'date', 'directory_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_daily_stats');
        Schema::dropIfExists('ad_campaigns');
    }
};
