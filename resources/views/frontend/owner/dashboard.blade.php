@extends('layouts.app')

@section('title', 'Firma Panelim')
@section('robots', 'noindex,nofollow')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
    @if(session('success'))<div class="mb-6 rounded-xl px-4 py-3 text-sm font-bold" style="background:#dcfce7;color:#166534;">{{ session('success') }}</div>@endif
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-black uppercase tracking-widest" style="color:var(--primary);">{{ $directory->name }}</p>
            <h1 class="mt-2 text-3xl font-black" style="color:var(--text);">Firma Panelim</h1>
            <p class="mt-2" style="color:var(--text_muted);">Hoş geldiniz, {{ auth()->user()->name }}. Bu rehberde sahip olduğunuz profiller burada.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('owner.campaigns') }}" class="relative overflow-hidden rounded-xl px-4 py-2 text-sm font-black text-white shadow-lg transition hover:-translate-y-0.5" style="background:linear-gradient(135deg,#ea580c,#dc2626);">
                <span class="mr-1.5 rounded bg-white/20 px-1.5 py-0.5 text-[10px] uppercase tracking-wider">Fırsat</span>
                100 Rehberde Yayın
            </a>
            <form method="POST" action="{{ route('owner.logout') }}">@csrf<button class="rounded-xl border px-4 py-2 text-sm font-bold" style="border-color:var(--border);color:var(--text);">Çıkış yap</button></form>
        </div>
    </div>

    <div class="mt-8 grid gap-5 md:grid-cols-2">
        @forelse($companies as $company)
            <section class="rounded-3xl border p-6" style="border-color:var(--border);background:var(--bg_card);box-shadow:var(--card_shadow);">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-xs font-black" style="color:{{ $company->status === 'active' ? '#15803d' : '#b45309' }};">{{ $company->status === 'active' ? 'YAYINDA' : 'İNCELEMEDE' }}</div>
                        <h2 class="mt-2 text-xl font-black" style="color:var(--text);">{{ $company->name }}</h2>
                        <p class="mt-1 text-sm" style="color:var(--text_muted);">{{ $company->category?->name }} · {{ $company->city?->name }}</p>
                        <p class="mt-2 text-xs font-black" style="color:var(--primary);">{{ $company->hasActivePremium() ? 'PREMIUM AKTİF' : 'STANDART PROFİL' }}</p>
                    </div>
                    @if($company->logo)<img src="{{ asset('storage/'.$company->logo) }}" class="h-12 w-12 rounded-xl object-contain" alt="">@endif
                </div>
                <div class="mt-5 flex items-center justify-between border-t pt-4 text-sm" style="border-color:var(--border);">
                    <span style="color:var(--text_muted);">Profil kapsamı <strong style="color:var(--text);">%{{ $company->profileCompletionScore() }}</strong></span>
                    <a href="{{ route('owner.company.edit', $company->slug) }}" class="font-black" style="color:var(--primary);">Profili düzenle →</a>
                </div>
                <div class="mt-4 flex flex-wrap gap-3 text-sm font-black">
                    <a href="{{ route('owner.offerings.index', $company) }}" class="rounded-xl border px-3 py-2" style="border-color:var(--border);color:var(--primary);">Ürün ve hizmetler →</a>
                    <a href="{{ route('owner.jobs.index', $company) }}" class="rounded-xl border px-3 py-2" style="border-color:var(--border);color:var(--primary);">İş ilanları →</a>
                </div>
            </section>
        @empty
            <section class="rounded-3xl border p-8 md:col-span-2" style="border-color:var(--border);background:var(--bg_card);">
                <h2 class="text-xl font-black" style="color:var(--text);">Bu rehberde henüz firmanız yok.</h2>
                <p class="mt-2" style="color:var(--text_muted);">Firmanızı eklemek için bu rehberdeki ücretsiz kayıt sayfasını kullanın.</p>
            </section>
        @endforelse
    </div>
</div>

@if($showCampaignPopup)
    <div id="campaign-offer-popup" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/75 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="campaign-offer-title">
        <div class="relative w-full max-w-lg overflow-hidden rounded-3xl bg-white shadow-2xl">
            <div class="p-7 text-white sm:p-9" style="background:linear-gradient(135deg,#7c2d12,#dc2626 55%,#f97316);">
                <button type="button" data-close-campaign-popup class="absolute right-4 top-4 rounded-full bg-white/15 px-3 py-1.5 text-lg font-bold text-white transition hover:bg-white/25" aria-label="Kapat">×</button>
                <p class="text-xs font-black uppercase tracking-[.2em] text-white/70">Firma sahiplerine özel fırsat</p>
                <div class="mt-5 flex items-end gap-3">
                    <strong data-campaign-counter="100" class="text-6xl font-black leading-none sm:text-7xl">0</strong>
                    <span class="mb-1.5 text-sm font-black uppercase tracking-wider text-white/80">Firma rehberinde<br>yayın fırsatı</span>
                </div>
                <h2 id="campaign-offer-title" class="mt-4 text-3xl font-black leading-tight sm:text-4xl">Rakiplerinizden sıyrılın.</h2>
                <p class="mt-3 text-lg font-bold text-white/90">{{ $campaignSettings->campaign_title }}</p>
            </div>
            <div class="p-7 sm:p-9">
                <p class="text-base leading-7" style="color:var(--text_muted);">Firmanızın görünürlüğünü tek bir rehberle sınırlamayın. Çoklu rehber yayınıyla daha geniş yerel arama kitlesine ulaşın.</p>
                <div class="mt-6 flex items-end justify-between rounded-2xl p-5" style="background:#fff7ed;">
                    <div><p class="text-sm font-bold" style="color:#9a3412;">Tek seferlik kampanya</p><p class="mt-1 text-xs" style="color:#c2410c;">Detayları WhatsApp'tan birlikte planlayalım.</p></div>
                    <strong class="text-3xl font-black" style="color:#9a3412;">{{ number_format((float) $campaignSettings->campaign_price, 0, ',', '.') }} TL</strong>
                </div>
                <a href="{{ route('owner.campaigns') }}" class="mt-6 flex w-full items-center justify-center rounded-xl px-5 py-4 text-center font-black text-white transition hover:opacity-90" style="background:#16a34a;">Kampanyayı İncele ve WhatsApp'tan Yaz</a>
                <button type="button" data-close-campaign-popup class="mt-4 w-full text-center text-sm font-bold" style="color:var(--text_muted);">Şimdilik istemiyorum</button>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('[data-close-campaign-popup]').forEach((button) => button.addEventListener('click', () => document.getElementById('campaign-offer-popup')?.remove()));
        document.querySelectorAll('[data-campaign-counter]').forEach((counter) => {
            const target = Number(counter.dataset.campaignCounter);
            const startedAt = performance.now();
            const duration = window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 0 : 1250;
            const tick = (now) => {
                const progress = duration ? Math.min((now - startedAt) / duration, 1) : 1;
                counter.textContent = Math.round(target * (1 - Math.pow(1 - progress, 3)));
                if (progress < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
        });
    </script>
@endif
@endsection
