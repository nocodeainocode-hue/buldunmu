@extends('layouts.app')

@section('title', 'Kampanyalar - Firma Paneli')
@section('robots', 'noindex,nofollow')

@section('content')
@php
    $price = (float) $settings->campaign_price;
    $perDirectory = $price > 0 ? number_format($price / 100, 0, ',', '.') : null;
    $firstCompany = $companies->first()?->name;
@endphp
<div class="mx-auto max-w-5xl px-4 py-10 pb-28 sm:px-6 lg:pb-10">
    <a href="{{ route('owner.dashboard') }}" class="text-sm font-bold" style="color:var(--primary);">← Firma paneline dön</a>
    <div class="mt-5 grid gap-6 lg:grid-cols-[1.25fr_.75fr]">
        <div class="space-y-6">
            <section class="overflow-hidden rounded-3xl border" style="border-color:var(--border);background:var(--bg_card);box-shadow:var(--card_shadow);">
                <div class="p-7 sm:p-9" style="background:linear-gradient(135deg,var(--primary),var(--secondary));color:#fff;">
                    <p class="text-xs font-black uppercase tracking-[.18em] text-white/70">Firma paneline özel kampanya</p>
                    <h1 class="mt-3 text-3xl font-black sm:text-4xl">{{ $campaignTitle }}</h1>
                    <p class="mt-4 max-w-xl leading-7 text-white/85">{{ $firstCompany ? $firstCompany.' için' : 'Firmanız için' }} çoklu rehber yayını, farklı temalarda görünürlük ve geniş yerel erişim fırsatı.</p>
                </div>
                <div class="p-7 sm:p-9">
                    <div class="flex flex-wrap gap-3">
                        @foreach(['100 farklı firma rehberinde yayın', 'Rehber bazlı bağımsız firma profili', 'İletişim, konum ve hizmet bilgilerinin yayını', 'Firmanızın şehrine uygun rehberlerde yayın', 'Tek noktadan kampanya takibi'] as $feature)
                            <span class="rounded-full px-3 py-2 text-sm font-bold" style="background:var(--primary_light);color:var(--primary);">✓ {{ $feature }}</span>
                        @endforeach
                    </div>
                    <div class="mt-8 flex flex-wrap items-end justify-between gap-4 rounded-2xl border p-5" style="border-color:var(--border);background:var(--bg);">
                        <div>
                            <p class="text-sm font-bold" style="color:var(--text_muted);">Kampanya fiyatı · tek seferlik</p>
                            <p class="mt-1 text-4xl font-black" style="color:var(--text);">{{ number_format($price, 0, ',', '.') }} TL</p>
                        </div>
                        @if($perDirectory)
                            <p class="rounded-xl px-4 py-2 text-sm font-black" style="background:#dcfce7;color:#166534;">Rehber başına yaklaşık {{ $perDirectory }} TL</p>
                        @endif
                    </div>
                    @if($whatsapp)
                        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="mt-5 flex w-full items-center justify-center rounded-xl px-5 py-4 text-center font-black text-white transition hover:opacity-90" style="background:#16a34a;">WhatsApp'tan Kampanya Bilgisi Al</a>
                        <p class="mt-3 text-center text-xs" style="color:var(--text_muted);">Ödeme, WhatsApp görüşmesinden sonra alınır. Önceden ödeme gerekmez.</p>
                    @else
                        <p class="mt-5 rounded-xl p-4 text-sm font-bold" style="background:#fef3c7;color:#92400e;">Kampanya WhatsApp hattı henüz tanımlanmadı.</p>
                    @endif
                </div>
            </section>

            <section class="rounded-3xl border p-7 sm:p-9" style="border-color:var(--border);background:var(--bg_card);box-shadow:var(--card_shadow);">
                <h2 class="text-xl font-black" style="color:var(--text);">Nasıl işler?</h2>
                <ol class="mt-5 grid gap-4 sm:grid-cols-3">
                    @foreach([
                        ['1', 'WhatsApp\'tan görüşürüz', 'Firmanızı ve hedeflerinizi birlikte netleştiririz.'],
                        ['2', 'Ödemeyi görüşmeden sonra alırız', 'Önceden ödeme yok; detayları onayladıktan sonra başlarız.'],
                        ['3', '10-15 günde kademeli yayın', 'Her gün birkaç rehberde yayınlanır, yayınlar zamana yayılarak tamamlanır.'],
                    ] as [$no, $title, $text])
                        <li class="rounded-2xl border p-4" style="border-color:var(--border);background:var(--bg);">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full text-sm font-black text-white" style="background:var(--primary);">{{ $no }}</span>
                            <p class="mt-3 text-sm font-black" style="color:var(--text);">{{ $title }}</p>
                            <p class="mt-1 text-xs leading-5" style="color:var(--text_muted);">{{ $text }}</p>
                        </li>
                    @endforeach
                </ol>
            </section>

            <section class="rounded-3xl border p-7 sm:p-9" style="border-color:var(--border);background:var(--bg_card);box-shadow:var(--card_shadow);">
                <h2 class="text-xl font-black" style="color:var(--text);">Sık sorulan sorular</h2>
                <div class="mt-4 divide-y" style="border-color:var(--border);">
                    @foreach([
                        ['Yayın ne zaman başlar?', 'Görüşme ve ödeme sonrasında başlar. Yayınlar 10-15 gün içinde kademeli olarak tamamlanır.'],
                        ['Ödemeyi nasıl yaparım?', 'Ödeme, WhatsApp görüşmesinden sonra birlikte belirlediğimiz yöntemle alınır. Siteden ödeme yapmanız gerekmez.'],
                        ['Arama sonuçlarında sıralama garantisi var mı?', 'Hayır. Kampanya, firmanızın birçok rehberde yayınlanmasını ve erişiminin artmasını sağlar; belirli bir sıralama ya da ziyaretçi sayısı vaat edilmez.'],
                    ] as [$question, $answer])
                        <details class="py-3" style="border-color:var(--border);">
                            <summary class="cursor-pointer text-sm font-black" style="color:var(--text);">{{ $question }}</summary>
                            <p class="mt-2 text-sm leading-6" style="color:var(--text_muted);">{{ $answer }}</p>
                        </details>
                    @endforeach
                </div>
            </section>
        </div>

        <aside class="h-fit rounded-3xl border p-6 lg:sticky lg:top-6" style="border-color:var(--border);background:var(--bg_card);box-shadow:var(--card_shadow);">
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
            @if($whatsapp)
                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="mt-5 hidden w-full items-center justify-center rounded-xl px-4 py-3 text-center text-sm font-black text-white lg:flex" style="background:#16a34a;">WhatsApp'tan yaz →</a>
            @endif
        </aside>
    </div>
</div>

@if($whatsapp)
    {{-- Mobilde her an erişilebilir WhatsApp çubuğu --}}
    <div class="fixed inset-x-0 bottom-0 z-40 border-t p-3 shadow-2xl lg:hidden" style="border-color:var(--border);background:var(--bg_card);">
        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="flex w-full items-center justify-center rounded-xl px-4 py-3.5 text-center text-sm font-black text-white" style="background:#16a34a;">
            WhatsApp'tan Bilgi Al · {{ number_format($price, 0, ',', '.') }} TL
        </a>
    </div>
@endif
@endsection
