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
            <form method="POST" action="{{ route('owner.logout') }}">@csrf<button class="rounded-xl border px-4 py-2 text-sm font-bold" style="border-color:var(--border);color:var(--text);">Çıkış yap</button></form>
        </div>
    </div>

    @php
        $campaignPrice = (float) $campaignSettings->campaign_price;
        $campaignPerDirectory = $campaignPrice > 0 ? number_format($campaignPrice / 100, 0, ',', '.') : null;
        $campaignCompany = $companies->first()?->name;
    @endphp
    <a href="{{ route('owner.campaigns', ['from' => 'banner']) }}" class="mt-6 flex flex-col gap-3 overflow-hidden rounded-2xl px-5 py-4 text-white shadow-lg transition hover:-translate-y-0.5 sm:flex-row sm:items-center sm:justify-between" style="background:linear-gradient(135deg,#7c2d12,#dc2626 55%,#f97316);" data-campaign-banner>
        <span class="flex flex-wrap items-center gap-x-3 gap-y-1">
            <span class="rounded bg-white/20 px-2 py-1 text-[10px] font-black uppercase tracking-wider">Fırsat</span>
            <span class="text-sm font-black sm:text-base">{{ $campaignCompany ?: 'Firmanız' }} 100 rehberde yayınlansın</span>
            <span class="text-xs font-bold text-white/85">{{ number_format($campaignPrice, 0, ',', '.') }} TL tek seferlik @if($campaignPerDirectory) · rehber başına ~{{ $campaignPerDirectory }} TL @endif</span>
        </span>
        <span class="inline-flex shrink-0 items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-black" style="color:#9a3412;">Detayları gör →</span>
    </a>

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
    @php
        $popupPrice = (float) $campaignSettings->campaign_price;
        $popupPerDirectory = $popupPrice > 0 ? number_format($popupPrice / 100, 0, ',', '.') : null;
        $popupCompany = $companies->first()?->name;
    @endphp
    <div id="campaign-offer-popup" class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto bg-slate-950/75 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="campaign-offer-title" data-popup-backdrop>
        <div class="relative my-auto w-full max-w-lg overflow-hidden rounded-3xl bg-white shadow-2xl">
            <div class="p-7 text-white sm:p-9" style="background:linear-gradient(135deg,#7c2d12,#dc2626 55%,#f97316);">
                <button type="button" data-close-campaign-popup class="absolute right-4 top-4 rounded-full bg-white/15 px-3 py-1.5 text-lg font-bold text-white transition hover:bg-white/25" aria-label="Kapat">×</button>
                <p class="text-xs font-black uppercase tracking-[.2em] text-white/70">Firma sahiplerine özel fırsat</p>
                <div class="mt-5 flex items-end gap-3">
                    <strong data-campaign-counter="100" class="text-6xl font-black leading-none sm:text-7xl">0</strong>
                    <span class="mb-1.5 text-sm font-black uppercase tracking-wider text-white/80">Firma rehberinde<br>yayın fırsatı</span>
                </div>
                <h2 id="campaign-offer-title" class="mt-4 text-3xl font-black leading-tight sm:text-4xl">Rakiplerinizden sıyrılın.</h2>
                <p class="mt-3 text-lg font-bold text-white/90">{{ $popupCompany ? $popupCompany.' için ' : '' }}{{ $campaignSettings->campaign_title }}</p>
            </div>
            <div class="p-7 sm:p-9">
                <ul class="space-y-2.5 text-sm font-bold" style="color:var(--text);">
                    <li class="flex gap-2"><span style="color:#16a34a;">✓</span> Firmanızın şehrine uygun 100 rehberde yayın</li>
                    <li class="flex gap-2"><span style="color:#16a34a;">✓</span> 10-15 gün içinde kademeli yayın</li>
                    <li class="flex gap-2"><span style="color:#16a34a;">✓</span> Ödeme, WhatsApp görüşmesinden sonra</li>
                </ul>
                <div class="mt-6 flex items-end justify-between gap-3 rounded-2xl p-5" style="background:#fff7ed;">
                    <div><p class="text-sm font-bold" style="color:#9a3412;">Tek seferlik kampanya</p>@if($popupPerDirectory)<p class="mt-1 text-xs font-bold" style="color:#c2410c;">Rehber başına yaklaşık {{ $popupPerDirectory }} TL</p>@endif</div>
                    <strong class="text-3xl font-black" style="color:#9a3412;">{{ number_format($popupPrice, 0, ',', '.') }} TL</strong>
                </div>
                <a href="{{ route('owner.campaigns', ['from' => 'popup']) }}" class="mt-6 flex w-full items-center justify-center rounded-xl px-5 py-4 text-center font-black text-white transition hover:opacity-90" style="background:linear-gradient(135deg,#ea580c,#dc2626);">Kampanya detaylarını gör</a>
                <button type="button" data-close-campaign-popup class="mt-4 w-full text-center text-sm font-bold" style="color:var(--text_muted);">Şimdilik istemiyorum</button>
            </div>
        </div>
    </div>
    <script>
        (() => {
            const popup = document.getElementById('campaign-offer-popup');
            if (!popup) return;
            const close = () => {
                if (!document.getElementById('campaign-offer-popup')) return;
                popup.remove();
                document.removeEventListener('keydown', onKey);
                fetch(@json(route('owner.campaigns.event')), {
                    method: 'POST', keepalive: true,
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '', 'Accept': 'application/json' },
                    body: JSON.stringify({ event: 'popup_dismiss' }),
                }).catch(() => {});
            };
            const onKey = (event) => { if (event.key === 'Escape') close(); };
            document.addEventListener('keydown', onKey);
            popup.addEventListener('click', (event) => { if (event.target === popup) close(); });
            popup.querySelectorAll('[data-close-campaign-popup]').forEach((button) => button.addEventListener('click', close));
            popup.querySelector('a[href]')?.focus();

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
        })();
    </script>
@endif
@endsection
