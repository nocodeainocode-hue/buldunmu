{{-- CEP · üyelik paketleri --}}
<div class="ph-pagehero">
    <nav class="ph-crumb" aria-label="Gezinme">
        <a href="{{ route('home') }}">Ana Sayfa</a><span>/</span><span>Paketler</span>
    </nav>
    <span class="ph-eyebrow">Vitrin · {{ $plans->count() }} paket</span>
    <h1>Hangi paket sana uygun?</h1>
    <p>Firman için doğru üyelik paketini seç; görünürlüğünü tek dokunuşta artır.</p>
</div>

@if($plans->isEmpty())
    <div class="ph-list"><div class="ph-empty">Şu anda üyelik paketi bulunmuyor. Daha sonra tekrar kontrol edin.</div></div>
@else
    <div class="ph-plans">
        @foreach($plans as $plan)
            @php
                $phCurrency = match($plan->currency) { 'USD' => '$', 'EUR' => '€', default => '₺' };
                $phPriceText = $plan->price > 0
                    ? $phCurrency . number_format((float) $plan->price, $plan->currency === 'TRY' ? 0 : 2, ',', '.')
                    : 'Ücretsiz';
                $phPeriod = match($plan->billing_period) { 'monthly' => 'Aylık', 'yearly' => 'Yıllık', 'onetime' => 'Tek seferlik', default => $plan->billing_period };
            @endphp
            <article class="ph-plan {{ $loop->iteration === 2 && $plans->count() > 2 ? 'ph-plan--featured' : '' }}">
                <span class="ph-eyebrow">Paket {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }} · {{ $phPeriod ?: 'Tek seferlik' }}</span>
                <h2>{{ $plan->name }}</h2>
                <div class="ph-plan__price">{{ $phPriceText }}</div>
                <ul>
                    @foreach(is_array($plan->features) ? $plan->features : [] as $feature)
                        <li>
                            <strong style="color:var(--ph-ink)">{{ $feature['title'] ?? '' }}</strong>
                            @if(!empty($feature['description']))<br>{{ $feature['description'] }}@endif
                        </li>
                    @endforeach
                </ul>
                <a class="ph-btn {{ $plan->price > 0 ? '' : 'ph-btn--ghost' }}" href="{{ route('pages.contact', ['subject' => 'uyelik']) }}">
                    {{ $plan->price > 0 ? 'Paketi seç' : 'Başvur' }} →
                </a>
            </article>
        @endforeach
    </div>
@endif

<div class="ph-note">Paket içeriğini birlikte şekillendirelim: hangi şehirde, hangi kategoride görünmek istediğini yaz, sana uygun sınıfı önerelim.</div>

<div class="ph-cta">
    <h2>Sormak istediklerin?</h2>
    <p>İletişim formundan yaz; aynı gün içinde dönüş yapalım.</p>
    <a class="ph-btn" href="{{ route('pages.contact') }}">İletişime geç →</a>
</div>
