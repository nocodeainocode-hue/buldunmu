<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('register_variants', function (Blueprint $table): void {
            $table->id();
            $table->string('key', 40)->unique();
            $table->string('name');
            $table->string('badge')->nullable();
            $table->string('headline');
            $table->text('subheadline')->nullable();
            $table->json('benefits')->nullable();
            $table->string('button_text', 60)->nullable();
            $table->string('status', 20)->default('active');
            $table->timestamps();
        });

        $now = now();
        $examples = [
            ['key' => 'ucretsiz', 'name' => 'Ücretsiz vurgusu', 'badge' => 'Ücretsiz firma kaydı',
                'headline' => 'Firmanız 2 dakikada {rehber} üzerinde yayında',
                'subheadline' => 'Kredi kartı yok, taahhüt yok. Telefon numaranız ve birkaç bilgiyle profiliniz hazır.',
                'button_text' => 'Firmamı Yayına Al'],
            ['key' => 'musteri', 'name' => 'Müşteri getirir vurgusu', 'badge' => 'Müşteri sizi arasın',
                'headline' => 'Sizi arayan müşteriler firmanızı bulsun',
                'subheadline' => 'Telefon ve WhatsApp bilgilerinizle {rehber} ziyaretçileri size doğrudan ulaşsın.',
                'button_text' => 'Müşteri Almaya Başla'],
            ['key' => 'sehir', 'name' => 'Şehir ve kategori reklamı ({sehir}, {kategori})', 'badge' => 'Yerel firmalar için',
                'headline' => '{sehir}\'de {kategori} arayanlar sizi bulsun',
                'subheadline' => '{sehir} bölgesinde hizmet veriyorsanız firmanızı {rehber} üzerinde ücretsiz yayınlayın.',
                'button_text' => 'Ücretsiz Profilimi Oluştur'],
        ];

        foreach ($examples as $row) {
            DB::table('register_variants')->insert($row + ['benefits' => null, 'status' => 'active', 'created_at' => $now, 'updated_at' => $now]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('register_variants');
    }
};
