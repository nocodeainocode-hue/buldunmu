<div class="dep dep-page">
    @include('partials.departures.page-hero', [
        'crumb' => 'Üyelik paketleri',
        'eyebrow' => 'Bilet sınıfı / Paketler',
        'title' => 'Hangi sınıftan yolculuk?',
        'description' => 'Firmanıza uygun paketi seçin; panoda öne çıkın.',
    ])
    <div class="dep-wrap dep-page__body">
        @if($plans->isEmpty())
            <div class="dep-empty">Şu anda tanımlı üyelik paketi yok. Bir süre sonra tekrar kontrol edin.</div>
        @else
            <div class="dep-grid">
                @foreach($plans as $plan)
                    @php
                        $depFeatures = is_array($plan->features) ? $plan->features : [];
                        $depPopular = $plan->slug === 'gold';
                    @endphp
                    <article class="dep-plan {{ $depPopular ? 'dep-plan--featured' : '' }}">
                        <div class="dep-plan__head">
                            <span class="dep-code" style="color:var(--amber-deep)">Bilet sınıfı {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}{{ $depPopular ? ' · En Popüler' : '' }}</span>
                            <h2>{{ $plan->name }}</h2>
                            <p class="dep-code" style="margin-top:8px">{{ match ($plan->billing_period) { 'monthly' => 'Aylık', 'yearly' => 'Yıllık', 'onetime' => 'Tek seferlik', default => $plan->billing_period } }}</p>
                        </div>
                        <div class="dep-plan__price">
                            @if($plan->price > 0)
                                {{ match ($plan->currency) { 'USD' => '$', 'EUR' => '€', default => '₺' } }}{{ number_format((float) $plan->price, $plan->currency === 'TRY' ? 0 : 2, ',', '.') }}
                                <small>{{ $plan->billing_period === 'monthly' ? 'aylık' : ($plan->billing_period === 'yearly' ? 'yıllık' : 'tek ödeme') }}</small>
                            @else
                                Ücretsiz
                                <small>standart kayıt</small>
                            @endif
                        </div>
                        @if(!empty($depFeatures))
                            <ul>
                                @foreach($depFeatures as $feature)
                                    <li><strong>{{ $feature['title'] ?? '' }}</strong>@if(!empty($feature['description'])) — {{ $feature['description'] }}@endif</li>
                                @endforeach
                            </ul>
                        @endif
                        <a class="dep-btn {{ $depPopular ? '' : 'dep-btn--ghost' }}" href="{{ route('pages.contact', ['subject' => 'uyelik']) }}">{{ $plan->price > 0 ? 'Paketi seç' : 'Başvur' }} →</a>
                    </article>
                @endforeach
            </div>
            <p class="dep-code" style="margin-top:22px">Paketler hakkında sorularınız için <a href="{{ route('pages.contact') }}" style="color:var(--amber-deep)">iletişim peronuna</a> yazın.</p>
        @endif
    </div>
</div>
