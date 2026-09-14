@extends('layouts.app')

@section('title', 'Kampanyalar - Firma Paneli')
@section('robots', 'noindex,nofollow')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6">
    <a href="{{ route('owner.dashboard') }}" class="text-sm font-bold" style="color:var(--primary);">← Firma paneline dön</a>
    <div class="mt-5 grid gap-6 lg:grid-cols-[1.2fr_.8fr]">
        <section class="overflow-hidden rounded-3xl border" style="border-color:var(--border);background:var(--bg_card);box-shadow:var(--card_shadow);">
            <div class="p-7 sm:p-9" style="background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;">
                <p class="text-xs font-black uppercase tracking-[.18em] text-white/70">Firma paneline özel kampanya</p>
                <h1 class="mt-3 text-3xl font-black sm:text-4xl">{{ $campaignTitle }}</h1>
                <p class="mt-4 max-w-xl leading-7 text-white/80">Firmanız için çoklu rehber yayını, farklı temalarda görünürlük ve geniş yerel erişim fırsatı.</p>
            </div>
            <div class="p-7 sm:p-9">
                <div class="flex flex-wrap gap-3">
                    @foreach(['100 farklı firma rehberinde yayın', 'Rehber bazlı bağımsız firma profili', 'İletişim, konum ve hizmet bilgilerinin yayını', 'Tek noktadan kampanya takibi'] as $feature)
                        <span class="rounded-full px-3 py-2 text-sm font-bold" style="background:var(--primary_light);color:var(--primary);">✓ {{ $feature }}</span>
                    @endforeach
                </div>
                <div class="mt-8 rounded-2xl border p-5" style="border-color:var(--border);background:var(--bg);">
                    <p class="text-sm font-bold" style="color:var(--text_muted);">Kampanya fiyatı</p>
                    <p class="mt-1 text-4xl font-black" style="color:var(--text);">{{ number_format((float) $settings->campaign_price, 0, ',', '.') }} TL</p>
                </div>
                @if($whatsapp)
                    <a href="https://wa.me/{{ $whatsapp }}?text={{ urlencode($message) }}" target="_blank" rel="noopener noreferrer" class="mt-5 flex w-full items-center justify-center rounded-xl px-5 py-4 text-center font-black text-white transition hover:opacity-90" style="background:#16a34a;">WhatsApp'tan Kampanya Bilgisi Al</a>
                @else
                    <p class="mt-5 rounded-xl p-4 text-sm font-bold" style="background:#fef3c7;color:#92400e;">Kampanya WhatsApp hattı henüz tanımlanmadı.</p>
                @endif
            </div>
        </section>

        <aside class="rounded-3xl border p-6" style="border-color:var(--border);background:var(--bg_card);box-shadow:var(--card_shadow);">
            <p class="text-xs font-black uppercase tracking-widest" style="color:var(--primary);">Kampanyaya dahil edilecek profil</p>
            <h2 class="mt-2 text-xl font-black" style="color:var(--text);">{{ $directory->name }}</h2>
            <div class="mt-5 space-y-3">
                @forelse($companies as $company)
                    <div class="rounded-xl border p-4 text-sm font-bold" style="border-color:var(--border);color:var(--text);">{{ $company->name }}</div>
                @empty
                    <p class="text-sm" style="color:var(--text_muted);">Henüz kampanyaya dahil edilecek firma profiliniz yok.</p>
                @endforelse
            </div>
            <p class="mt-5 text-xs leading-5" style="color:var(--text_muted);">WhatsApp mesajında firma adınız otomatik olarak eklenir. Böylece teklif hangi profil için istendiği net olur.</p>
        </aside>
    </div>
</div>
@endsection
