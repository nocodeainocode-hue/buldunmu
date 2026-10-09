<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kayıt anındaki reklam/kaynak bilgisi (ilk temas).
            $table->string('utm_source', 191)->nullable()->after('campaign_popup_seen_at');
            $table->string('utm_medium', 191)->nullable()->after('utm_source');
            $table->string('utm_campaign', 191)->nullable()->after('utm_medium');
            $table->string('utm_term', 191)->nullable()->after('utm_campaign');
            $table->string('utm_content', 191)->nullable()->after('utm_term');
            $table->string('click_id', 191)->nullable()->after('utm_content'); // gclid / fbclid / msclkid
            $table->string('referrer_host', 191)->nullable()->after('click_id');
            $table->unsignedBigInteger('signup_directory_id')->nullable()->after('referrer_host');

            $table->index('utm_source');
            $table->index('signup_directory_id');
        });

        Schema::create('owner_campaign_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('directory_id')->nullable();
            $table->string('event', 40); // popup_shown | popup_dismiss | page_view | whatsapp_click
            $table->string('source', 20)->nullable(); // popup | banner | button | direct
            $table->timestamp('created_at')->useCurrent();

            $table->index(['event', 'created_at']);
            $table->index(['user_id', 'event']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('owner_campaign_events');

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['utm_source']);
            $table->dropIndex(['signup_directory_id']);
            $table->dropColumn(['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'click_id', 'referrer_host', 'signup_directory_id']);
        });
    }
};
