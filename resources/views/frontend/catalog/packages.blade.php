<div class="kat kat-page">
    @include('partials.catalog.band', [
        'crumb' => 'Üyelik paketleri',
        'eyebrow' => 'Abonelik / Paketler',
        'title' => 'Hangi pakette yer alıyorsunuz?',
        'description' => 'Firmanıza uygun paketi seçin; katalogda öne çıkın.',
        'folio' => str_pad((string) $plans->count(), 2, '0', STR_PAD_LEFT),
    ])
    <div class="kat-wrap">
        @if($plans->isEmpty())
            <div class="kat-empty">Şu anda tanımlı üyelik paketi yok. Bir süre sonra tekrar kontrol edin.</div>
        @else
            <div class="kat-plans">
                @foreach($plans as $plan)
                    @php
                        $katFeatures = is_array($plan->features) ? $plan->features : [];
                        $katPopular = $loop->index === 1;
                    @endphp
                    <article class="kat-plan {{ $katPopular ? 'kat-plan--featured' : '' }}">
                        <span class="kat-plan__cat">Paket {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }} · {{ match ($plan->billing_period) { 'monthly' => 'Aylık', 'yearly' => 'Yıllık', 'onetime' => 'Tek seferlik', default => $plan->billing_period } }}</span>
                        @if($katPopular)<span class="kat-plan__badge">Önerilen</span>@endif
                        <h2>{{ $plan->name }}</h2>
                        <p class="kat-plan__price">
                            @if($plan->price > 0)
                                {{ match ($plan->currency) { 'USD' => '$', 'EUR' => '€', default => '₺' } }}{{ number_format((float) $plan->price, $plan->currency === 'TRY' ? 0 : 2, ',', '.') }}
                                <small>{{ $plan->billing_period === 'monthly' ? 'aylık' : ($plan->billing_period === 'yearly' ? 'yıllık' : 'tek ödeme') }}</small>
                            @else
                                Ücretsiz
                                <small>standart kayıt</small>
                            @endif
                        </p>
                        @if(!empty($katFeatures))
                            <ul>
                                @foreach($katFeatures as $feature)
                                    <li><strong>{{ $feature['title'] ?? '' }}</strong>@if(!empty($feature['description'])) — {{ $feature['description'] }}@endif</li>
                                @endforeach
                            </ul>
                        @endif
                        <a class="kat-btn {{ $katPopular ? '' : 'kat-btn--ghost' }}" href="{{ route('pages.contact', ['subject' => 'uyelik']) }}">{{ $plan->price > 0 ? 'Paketi seç' : 'Başvur' }} →</a>
                    </article>
                @endforeach
            </div>
            <p class="kat-note" style="margin-top:34px">Paket içerikleri firmadan firmaya değişebilir; güncel koşullar için <a href="{{ route('pages.contact') }}" style="color:var(--secondary)">iletişim sayfasına</a> yazın.</p>
        @endif
    </div>
</div>
