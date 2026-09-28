<div class="ib ib-page">
    @include('partials.ilan.band', [
        'crumb' => 'Üyelik paketleri',
        'eyebrow' => 'Abonelik / Paketler',
        'title' => 'Üyelik paketleri',
        'description' => 'Firmanıza uygun paketi seçin; borsada öne çıkın.',
    ])
    <div class="ib-wrap">
        @if($plans->isEmpty())
            <div class="ib-empty" style="border:1px solid var(--border);border-radius:var(--border_radius);background:var(--bg_card);margin-top:14px">Şu anda tanımlı üyelik paketi yok. Bir süre sonra tekrar kontrol edin.</div>
        @else
            <div class="ib-plans" style="margin-top:14px">
                @foreach($plans as $plan)
                    @php
                        $ibFeatures = is_array($plan->features) ? $plan->features : [];
                        $ibPopular = $loop->index === 1;
                    @endphp
                    <article class="ib-plan {{ $ibPopular ? 'ib-plan--featured' : '' }}">
                        <span class="ib-plan__cat">Paket {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }} · {{ match ($plan->billing_period) { 'monthly' => 'Aylık', 'yearly' => 'Yıllık', 'onetime' => 'Tek seferlik', default => $plan->billing_period } }}</span>
                        @if($ibPopular)<span class="ib-plan__badge">En çok tercih edilen</span>@endif
                        <h2>{{ $plan->name }}</h2>
                        <p class="ib-plan__price">
                            @if($plan->price > 0)
                                {{ match ($plan->currency) { 'USD' => '$', 'EUR' => '€', default => '₺' } }}{{ number_format((float) $plan->price, $plan->currency === 'TRY' ? 0 : 2, ',', '.') }}
                                <small>{{ $plan->billing_period === 'monthly' ? 'aylık' : ($plan->billing_period === 'yearly' ? 'yıllık' : 'tek ödeme') }}</small>
                            @else
                                Ücretsiz
                                <small>standart kayıt</small>
                            @endif
                        </p>
                        @if(!empty($ibFeatures))
                            <ul>
                                @foreach($ibFeatures as $feature)
                                    <li><strong>{{ $feature['title'] ?? '' }}</strong>@if(!empty($feature['description'])) — {{ $feature['description'] }}@endif</li>
                                @endforeach
                            </ul>
                        @endif
                        <a class="ib-btn {{ $ibPopular ? '' : 'ib-btn--ghost' }}" href="{{ route('pages.contact', ['subject' => 'uyelik']) }}">{{ $plan->price > 0 ? 'Paketi seç' : 'Başvur' }}</a>
                    </article>
                @endforeach
            </div>
            <p class="ib-note" style="margin-top:18px">Paket içerikleri firmadan firmaya değişebilir; güncel koşullar için <a href="{{ route('pages.contact') }}" style="color:var(--primary);font-weight:700">iletişim ekranına</a> yazın.</p>
        @endif
    </div>
</div>
